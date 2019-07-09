<?php
class HackerNews
{
    //private static $storyIds;
    public static function getStoryIds($type = "top")
    {
        if(!array_search($type,["top","new","best","ask","show","job"])){
            $type = "top";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://hacker-news.firebaseio.com/v0/' . $type . 'stories.json');
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }

    /**
     *
     */
    public static function getStories($storyIds, $start = 0, $amount = 0)
    {
        if ($amount > 0) {
            $storyIds_sliced = array_slice($storyIds, $start, $amount);
        } else {
            $storyIds_sliced = $storyIds;
        }
        $curlHandles = array();
        $stories = array();
        $multiCurlHandle = curl_multi_init();

        foreach ($storyIds_sliced as $storyId) {
            $ch = curl_init();
            array_push($curlHandles, $ch);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'https://hacker-news.firebaseio.com/v0/item/' . $storyId . '.json');
            curl_multi_add_handle($multiCurlHandle, $ch);
        }

        // Execute the multi handle
        do {
            $status = curl_multi_exec($multiCurlHandle, $active);
            if ($active) {
                curl_multi_select($multiCurlHandle);
            }
        } while ($active && $status == CURLM_OK);

        // Close handles, populate return array
        foreach ($curlHandles as $ch) {
            $stories[] = json_decode(curl_multi_getcontent($ch)); // Faster than array_push
            curl_multi_remove_handle($multiCurlHandle, $ch);
        }
        curl_multi_close($multiCurlHandle);
        return $stories;
    }
}
