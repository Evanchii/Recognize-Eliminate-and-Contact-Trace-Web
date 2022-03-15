<?php
if (!function_exists('createLog')) {
    function createLog($category, $desc, $uid)
    {
        include ROOT_PATH . '/includes/dbconfig.php';
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $currTS = time();

        $logRef = $database->getReference('Logs/');
        if (!isset($_SESSION['uid'])) 
            $_SESSION['uid'] = $uid;
        $userARef = $database->getReference('Users/' . $_SESSION['uid'] . '/info');

        $typeA = $userARef->getChild('Type')->getValue();
        if ($typeA == 'visitor') {
            $userA = $userARef->getChild('lName')->getValue() . ', ' . $userARef->getChild('fName')->getValue() . ' ' . $userARef->getChild('mName')->getValue();
        } elseif ($typeA == 'establishment') {
            $userA = $userARef->getChild('name')->getValue() . ' - ' . $userARef->getChild('branch')->getValue();
        } elseif ($typeA == 'sub-establishment') {
            $userA = $userARef->getChild('username')->getValue() . ' ' . $userARef->getChild('name')->getValue() . ' - ' . $userARef->getChild('branch')->getValue();
        } elseif ($typeA == 'sub-admin') {
            $userA = $userARef->getChild('username')->getValue();
        } else {
            $userA = 'Admin';
        }

        if (!str_icontains($desc, 'their')) {
            $userBRef = $database->getReference('Users/' . $uid . '/info');
            $type = $userBRef->getChild('Type')->getValue();
            if ($type == 'visitor') {
                $userB = $userBRef->getChild('lName')->getValue() . ', ' . $userBRef->getChild('fName')->getValue() . ' ' . $userBRef->getChild('mName')->getValue();
            } elseif ($type == 'establishment') {
                $userB = $userBRef->getChild('name')->getValue() . ' - ' . $userBRef->getChild('branch')->getValue();
            } elseif ($type == 'sub-establishment') {
                $userB = $userBRef->getChild('username')->getValue() . ' ' . $userBRef->getChild('name')->getValue() . ' - ' . $userBRef->getChild('branch')->getValue();
            } elseif ($type == 'sub-admin') {
                $userB = $userBRef->getChild('username')->getValue();
            } else {
                $userB = 'Admin';
            }

            $desc .= $userB . ' (' . $uid . ')';
        }

        // Prevent overwritting
        while($logRef->getChild($currTS)->getSnapshot()->hasChildren()) {
            $currTS++;
        }

        $logRef->update([
            $currTS => [
                'category' => $category,
                'description' => $userA . ' (' . $_SESSION['uid'] . ') ' . $desc,
                'ip' => $_SERVER['REMOTE_ADDR'],
            ]
        ]);
        unset($_SESSION['uid']);
    }
}

if (!function_exists('str_icontains')) {
    function str_icontains($haystack, $needle)
    {
        $smallhaystack = strtolower($haystack);  // make the haystack lowercase, which essentially makes it case insensitive
        $smallneedle = strtolower($needle);  // makes the needle lowercase, which essentially makes it case insensitive
        if (str_contains($smallhaystack, $smallneedle)) {  // compares the lowercase strings
            return true;  // returns true (wow)
        } else {
            return false;  // returns false (wow)
        }
    }
}
