<?php 
    function convertDateTimeToString($date) {
        if($date === null) {
            return "";
        }
        $current = new DateTime();
        $datetime = new DateTime($date);
        $interval = $datetime->diff($current);
    
        if ($interval->y > 0) {
            return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
        } elseif ($interval->m > 0) {
            return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
        } elseif ($interval->d > 0) {
            return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
        } else {
            return $interval->s . ' second' . ($interval->s > 1 ? 's' : '') . ' ago';
        }
    }
?>
