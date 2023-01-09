<html>
<body>
<p>
    Kedves {{$user->lastname}} {{$user->firstname}}!<br /><br />
    A termék {{$productChangeRequest->product ? 'módosítási' : 'feltöltési'}} kérésed elutasításra került.
    <br /><br />
    Üdvözlettel: Krémmánia
</p>
</body>
</html>
