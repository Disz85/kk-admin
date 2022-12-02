<?php

namespace app\Console\Commands\Import;

use App\Enum\SkinConcernEnum;
use App\Enum\SkinTypeEnum;
use App\Models\User;
use App\XMLReaders\UserXMLReader;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportUsers extends Command
{
    public const TYPE_ASPNET = 'AspNetUsers';
    public const TYPE_USERS = 'Users';

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

        switch($type) {
            case self::TYPE_ASPNET:
                $this->importAspNetUsers($userXMLReader, $path);

                break;
            case self::TYPE_USERS:
                $this->importUsers($userXMLReader, $path);

                break;

            default:
                $this->info("\n This XML is wrong, maybe it's not a user file.");

                break;
        }
    }

    /** Import ApsNetUsers */
    private function importAspNetUsers($userXMLReader, $path)
    {
        $progress = $this->output->createProgressBar($userXMLReader->count($path));
        $progress->start();

        $skipped = 0;
        $userEmails = [];
        $wrongEmails = [];
        $userXMLReader->read($path, function (array $data) use ($progress, &$skipped, &$userEmails, &$wrongEmails) {
            $email = $this->stripAccents(strtolower(trim($data['Email'])));
            $email = preg_replace('/\s+/', '', $email);

            if (! in_array($email, $userEmails) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $userEmails[] = $email;

                $user = User::where(['email' => $email])->first() ?? new User();

                $user->legacy_id = trim($data['Id']);
                $user->username = trim($data['UserName']);
                $user->email = $email;

                $date = trim($data['CreateDate']);

                $format = 'Y-m-d\TH:i:s';
                if (str_contains($date, '.')) {
                    $format = 'Y-m-d\TH:i:s.u';
                }

                $date = Carbon::createFromFormat($format, $date);
                $user->created_at = $date->format('Y-m-d H:i:s');

                $user->save();
            } else {
                $skipped++;
            }
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $wrongEmails[] = $email;
            }

            $progress->advance();
        });

        $progress->finish();

        $this->info("\n ASP.NET Users importing is finished. Number of skipped records: " . $skipped);
        $this->info("\n Wrong emails: " . implode(", ", $wrongEmails));
    }

    /** Import Users */
    private function importUsers($userXMLReader, $path)
    {
        $progress = $this->output->createProgressBar($userXMLReader->count($path));
        $progress->start();

        $userXMLReader->read($path, function (array $data) use ($progress) {
            $user = User::where(['legacy_nickname' => $data['NickName']])->orWhere(['username' => $data['NickName']])->first() ?? new User();

            if (! $user->legacy_username) {
                $user->legacy_nickname = $data['NickName'];
                $user->username = $data['NickName'];
            }

            $user->slug = array_key_exists('Slug', $data) ? $data['Slug'] : null;
            $user->birth_year = array_key_exists('BirthYear', $data) ? $data['BirthYear'] : null;
            $user->skin_type = array_key_exists('SkinTypeID', $data) ? $this->getSkinType($data['SkinTypeID']) : SkinTypeEnum::NORMAL;
            $user->skin_concern = array_key_exists('SkinConcernID', $data) ? $this->getSkinConcern($data['SkinConcernID']) : SkinConcernEnum::NONE;
            $user->save();

            $progress->advance();
        });

        $progress->finish();

        $this->info("\n Users importing is finished.");
    }

    private function getSkinType($skinTypeId)
    {
        return match ($skinTypeId) {
            '2-1' => SkinTypeEnum::DRY,
            '2-2' => SkinTypeEnum::NORMAL,
            '2-3' => SkinTypeEnum::COMBINED,
            '2-4' => SkinTypeEnum::GREASY,
        };
    }

    private function getSkinConcern($skinConcernId)
    {
        return match ($skinConcernId) {
            '3-1' => SkinConcernEnum::ACNE,
            '3-2' => SkinConcernEnum::REDNESS,
            '3-3' => SkinConcernEnum::UNEVEN_SKIN,
            '3-4' => SkinConcernEnum::PIGMENT_SPOTS,
            '3-5' => SkinConcernEnum::WIDE_PORES,
            '3-6' => SkinConcernEnum::SKIN_AGING,
            '3-7' => SkinConcernEnum::DEHYDRATED_SKIN,
            '3-8' => SkinConcernEnum::HYPERSENSITIVITY,
        };
    }

    private function stripAccents($str)
    {
        return strtr(utf8_decode($str), utf8_decode('áéíóöőúüű'), 'aeiooouuu');
    }
}
