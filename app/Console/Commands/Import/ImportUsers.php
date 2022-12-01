<?php

namespace app\Console\Commands\Import;

use App\Enum\SkinConcernEnum;
use App\Enum\SkinTypeEnum;
use App\Models\User;
use App\XMLReaders\UserXMLReader;
use Illuminate\Console\Command;

class ImportUsers extends Command {

    const TYPE_ASPNET      = 'AspNetUsers';
    const TYPE_USERS       = 'Users';
    const TYPE_CONNECTION  = 'UserNickConnections';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users
                            {--path= : The path of the XML file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports users from XML files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(UserXMLReader $userXMLReader): void
    {
        $path = $this->option('path');
        $type = $userXMLReader->getType($path);

        switch($type){
            case self::TYPE_ASPNET:
                $this->importAspNetUsers($userXMLReader, $path); break;
            case self::TYPE_USERS:
                $this->importUsers($userXMLReader, $path); break;
            case self::TYPE_CONNECTION:
                $this->importNicknameUsernameConnections($userXMLReader, $path); break;
            default:
                $this->info("\n This XML is wrong, maybe it's not a user file.");
                break;
        }
    }

    /** Import ApsNetUsers */
    private function importAspNetUsers($userXMLReader, $path){
        $progress = $this->output->createProgressBar($userXMLReader->count($path));
        $progress->start();

        $skipped = 0;
        $userEmails = [];
        $wrongEmails = [];
        $userXMLReader->read($path, function (array $data) use ($progress, &$skipped, &$userEmails, &$wrongEmails) {
            $email = $this->stripAccents(strtolower(trim($data['Email'])));
            $email = preg_replace('/\s+/', '', $email);

            if(!in_array($email, $userEmails) && filter_var($email, FILTER_VALIDATE_EMAIL)){
                $userEmails[] = $email;

                $user = User::where(['email' => $email])->first() ?? new User();

                $user->username = trim($data['UserName']);
                $user->email = $email;
                $user->created_at = $data['CreateDate'];
                $user->save();
            }
            else{
                $skipped++;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $wrongEmails[] = $email;
            }

            $progress->advance();
        });

        $progress->finish();

        $this->info("\n ASP.NET Users importing is finished. Number of skipped records: " . $skipped);
        $this->info("\n Wrong emails: " . implode(", ", $wrongEmails));
    }

    /** Import Users */
    private function importUsers($userXMLReader, $path){
        $progress = $this->output->createProgressBar($userXMLReader->count($path));
        $progress->start();

        $userXMLReader->read($path, function (array $data) use ($progress) {

            if($data['NickName'] !== '----------'){
                $user = User::where(['legacy_nickname' => $data['NickName']])->orWhere(['username' => $data['NickName']])->first() ?? new User();

                if(!$user->legacy_username){
                    $user->legacy_nickname = $data['NickName'];
                    $user->username = $data['NickName'];
                }

                $user->slug = array_key_exists('Slug', $data) ? $data['Slug'] : NULL;
                $user->birth_year = array_key_exists('BirthYear', $data) ? $data['BirthYear'] : NULL;
                $user->skin_type = array_key_exists('SkinTypeID', $data) ? $this->getSkinType($data['SkinTypeID']) : SkinTypeEnum::NORMAL;
                $user->skin_concern = array_key_exists('SkinConcernID', $data) ? $this->getSkinConcern($data['SkinConcernID']) : SkinConcernEnum::NONE;
                $user->save();
            }

            $progress->advance();
        });

        $progress->finish();

        $this->info("\n Users importing is finished.");
    }

    /** Import Nickname-Username connections */
    private function importNicknameUsernameConnections($userXMLReader, $path){
        $progress = $this->output->createProgressBar($userXMLReader->count($path));
        $progress->start();

        $skipped = 0;
        $userNames = [];
        $userXMLReader->read($path, function (array $data) use ($progress, &$skipped, &$userNames) {
            $username = trim($data['UserName']);
            if(!in_array($username, $userNames)){
                $userNames[] = $data['UserName'];
                $user = User::where(['username' => $username])->first() ?? new User();

                $user->legacy_username = $username;
                $user->legacy_nickname = $data['NickName'];
                $user->username = $username;
                $user->timestamps = false;
                $user->save();
            }
            else{
                $skipped++;
            }

            $progress->advance();
        });

        $progress->finish();

        $this->info("\n Nickname - Username importing is finished. Skipped because of duplicated usernames: " . $skipped);
    }

    private function getSkinType($skinTypeId){
        $skinTypes = [
            '2-1' => SkinTypeEnum::DRY,
            '2-2' => SkinTypeEnum::NORMAL,
            '2-3' => SkinTypeEnum::COMBINED,
            '2-4' => SkinTypeEnum::GREASY
        ];

        return $skinTypes[$skinTypeId];
    }

    private function getSkinConcern($skinConcernId){
        $skinConcerns = [
            '3-1' => SkinConcernEnum::ACNE,
            '3-2' => SkinConcernEnum::REDNESS,
            '3-3' => SkinConcernEnum::UNEVEN_SKIN,
            '3-4' => SkinConcernEnum::PIGMENT_SPOTS,
            '3-5' => SkinConcernEnum::WIDE_PORES,
            '3-6' => SkinConcernEnum::SKIN_AGING,
            '3-7' => SkinConcernEnum::DEHYDRATED_SKIN,
            '3-8' => SkinConcernEnum::HYPERSENSITIVITY
        ];

        return $skinConcerns[$skinConcernId];
    }

    private function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('áéíóöőúüű'), 'aeiooouuu');
    }
}
