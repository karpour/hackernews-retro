<?php

require './HackerNews.php';

$pageSize = 10;

$type = isset($_GET["s"]) ? $_GET["s"] : "";
$page = isset($_GET["p"]) ? (int) $_GET["p"] : 0;
$page = $page > 0 ? $page : 1;


$startIdx = ($page - 1) * $pageSize;
$stories = HackerNews::getStories(HackerNews::getStoryIds($type), $startIdx, $pageSize);

$displayIdx = $startIdx + 1;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hacker News</Title>
        <style type="text/css">
            A{text-decoration:none}
        </style>
</head>

<body link="#000000" vlink="#828282">
    <font face="Arial" size="2">
        <table align="center" width="620" cellspacing="0" cellpadding="0" bgcolor="#f6f6ef">
            <tbody>
                <tr>
                    <td bgcolor="#ff6600" VALIGN="middle">
                        <img src="y18.gif">
                        <font face="Arial" size="2"><b>Hacker News</b> <a href="?s=new">new</a> | past | comments | <a href="?s=ask">ask</a> | <a href="?s=show">show</a> | <a href="?s=jobs">jobs</a> |
                            <?php echo date('Y-m-d', time()); ?></font>
                    </td>

                </tr>
                <tr>
                    <td>
                        <table>
                            <tbody>
                                <?php foreach ($stories as $idx => $story) {?>
                                <tr class="athing" id="20353734">
                                    <td valign="top" align="right">
                                        <font color="#828282">
                                            <?php echo $displayIdx++; ?>.
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <a id="up_20353734" href="vote?id=20353734&amp;how=up&amp;goto=front">
                                                <img src="ga.gif">
                                            </a>
                                    </td>
                                    <td><a
                                            href="<?php echo $story->url; ?>"
                                            >
                                            <?php echo $story->title; ?>
                                        </a>
                                        <font size="1">
                                        (<?php echo parse_url($story->url)['host']; ?>)
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td>
                                        <font size="1">
                                        <?php echo $story->score ?> points by
                                            <a href="user?id=mattkevan"><?php echo $story->by ?></a>
                                            <a href="item?id=20353734"><?php echo date('Y-m-d', $story->time); ?></a> |
                                            <a href="item?id=20353734"><?php echo $story->descendants ?>&nbsp;comments</a>
                                        </font>
                                    </td>
                                </tr>
                                <?php }?>
                                <tr height="30" valign="bottom">
                                    <td colspan="2">
                                    </td>
                                    <td class="title">
                                        <a href="/?<?php echo ($type != "" ? "s=".$type."&" : "")."p=".($page+1); ?>" rel="next">More</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </font>
</body>

</html>