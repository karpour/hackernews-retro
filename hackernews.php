<?php

/**
 * Class to access the {@link https://github.com/HackerNews/API Hacker news API}
 * @author Thomas Novotny <dev@thomasnovotny.com>
 */

class HackerNews
{
    /**
     * Return list of story IDs for a specific topic. If input string is invalid, story IDs for the "top" category will be returned.
     *
     * @param string Any one of "top","new","best","ask","show" or "job"
     * @return array Array of story IDs
     */
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
     * Returns an array of stories, as defined at {@link https://github.com/HackerNews/API}
     *
     * @param array $storyIds
     * @param integer $start Star index
     * @param integer $amount Amount of stories to load
     * @return array Array of stories
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
