<?php
require('buffer.php');

class AMMyBuffer{

    public function sendUpdate($title,$link,$pictureurl='',$thumbnailurl=''){
        $buffer = new CBBufferApp('<YOUR_CLIENT_ID>', '<YOUR_CLIENT_SECRET>', '<YOUR_CALLBACK_URL>');
        if (!$buffer->ok) {
            return 'Please login to buffer in another tab.';
        } else {
            //this pulls all of the logged in user's profiles
            $profiles = $buffer->go('/profiles');
            if (is_array($profiles)) {
                foreach ($profiles as $profile) {
                    //this creates a status on each one
                    $buffer->go('/updates/create', array(
                        'text' => $title,
                        'profile_ids[]' => $profile->id,
                        'shorten'=>true,
                        'now'=>true,
                        'top'=>true,
                        'media[title]'=>  $title,
                        'media[link]'=>  $link,
                        'media[picture]'=>  $pictureurl,
                        'media[thumbnail]'=>  $thumbnailurl,
                    ));

                }
                return ' Updated On Buffer Also!';
            }
        }
    }

}
