<?php
function reqPermission($level)
{
	if ($_SESSION['type']<$level) { die(); }
}

function appendToPost($conn, $board, $postid, $text)
{
	if (is_numeric($postid))
	{
		$post = $conn->query("SELECT * FROM posts WHERE id=".$postid." AND board='".$board."'");
		if ($post->num_rows == 1)
		{
			$pdata = $post->fetch_assoc();
			$text = $conn->real_escape_string($text);
			$new_text = $conn->real_escape_string($pdata['comment'])."\n\n".$text;
			$conn->query("UPDATE posts SET comment='".$new_text."', raw=2 WHERE id=".$postid." AND board='".$board."'");
			if ($pdata['resto'] == 0)
			{
				generateView($conn, $board, $pdata['id']);
			} else {
				generateView($conn, $board, $pdata['resto']);
			}
			generateView($conn, $board);
		}
	}
}

function canBoard($board)
{
	if (($_SESSION['boards'] != "*") && ($_SESSION['type'] != 2))
	{
		$boards = explode(",", $_SESSION['boards']);
		if (in_array($board, $boards))
		{
			return 1;
		} else {
			die();
		}
	} else {
		return 1;
	}
}

function checkForUpdates()
{
	if ((defined(MITSUBA_VERSION)) && (MITSUBA_VERSION != "disabled"))
	{
		//magic here
	}
}

function updateConfig($conn, $config)
{
	foreach ($config as $key => $value)
	{
		$conn->query("UPDATE config SET value='".$conn->real_escape_string($value)."' WHERE name='".$key."';");
	}
}
?>