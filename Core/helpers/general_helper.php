<?php
/**
 * Function for finding different between two dates
 * @var date_1 date of the format YYYY-MM-DD
 * @var date_2 date of the format YYYY-MM-DD
 */
function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);

	$interval = date_diff($datetime1, $datetime2);

	return $interval->format($differenceFormat);
}

// Pagination helper function
function paginationLink($total_items, $items_per_page)
{
	$pages = ceil($total_items / $items_per_page);
	$prev_page = isset($_GET["page"]) && $_GET["page"] > 1? $_GET["page"] - 1 : 1;
	$next_page = isset($_GET["page"]) && $_GET["page"] < $pages? $_GET["page"] + 1 : $pages;
	$current_page = isset($_GET["page"]) && !empty($_GET["page"])? $_GET["page"] : 1;
	// $get = $_SERVER["QUERY_STRING"];

	$link = htmlspecialchars($_SERVER["PHP_SELF"]);
	$html = '<div class="pagination">
			<a class="btn txt-dark bg-pagination" href="'. $link . '?page=' . $prev_page .'" class="href">&laquo;</a>';

	for($i = 1; $i <= $pages; $i++) {
		if($i == $current_page)
			$html .= '<a class="btn txt-dark pagination-active bg-pagination" href="'. $link . '?page=' . $i .'" class="href">'. $i .'</a>';
		else
			$html .= '<a class="btn txt-dark bg-pagination" href="'. $link . '?page=' . $i .'" class="href">'. $i .'</a>';
	}
	$html .= '<a class="btn txt-dark bg-pagination" href="'. $link . '?page=' . $next_page .'" class="href">&raquo;</a>';
	$html .= "</div>";

	return $html;
}

// Building dash header navigation (e.g. Dashboard > Member > Add Member)
function showRunningPage()
{
	$page = $_SERVER["SCRIPT_NAME"];
	$page = explode("/", $page);
	$page = $page[count($page) - 1];
	$page = trim($page, '/');
	$page = explode(".", $page)[0];

	return ucfirst(strtolower($page));
}

function title_case($string)
{
	return ucwords(strtolower($string));
}

function genFileName($length = 20)
{
	 $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	 $clen   = strlen( $chars )-1;
	 $id  = '';

	 for ($i = 0; $i < $length; $i++) {
			 $id .= $chars[mt_rand(0,$clen)];
	 }
	 return ($id);
}

function genOptionHtmlTag($from, $to, $selected = null)
{
	$html = '';
	if ($selected) {
		for ($i = $from; $i <= $to; $i++) { 
			if($i == $selected)
				$html .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			else
				$html .= '<option value="' . $i . '">' . $i . '</option>';
		}
	} else {
		for ($i = $from; $i <= $to; $i++) { 
			$html .= '<option value="' . $i . '">' . $i . '</option>';
		}
	}

	return $html;
}



/**
 * --------------------------------------------------------
 *  Remember last post data
 * --------------------------------------------------------
 * These four functions are for handling last post data
 * this is useful when we need to pre-populate the form
 * when a validation error occurs and we redirect back
 * to previous page.
 *
 * Usage:
 * o When you need to remember last post request, then call
 *    remember_post();
 *
 * o Retrieve old post data in view
 *    old_post('name', $default_value = 'Guest');
 *
 * o Check old post data exist or not
 *    has_remembered_post();
 *
 * o Destroy when you used it and no longer needed.
 *   Best place to use this function is after validation
 *   of form in post route.
 *   forget_post();
 *
 *
 * Example:
 *    First call the remember_post() function above validation
 *    inside post route method.
 *    public function store()
 *    {
 *     		remember_post();
 *			// do you validation stuff here
 *			// if validation has error then it will redirect
 * 			// back to previous page immediately.
 *			forget_post();
 *    }
 *
 *    Retrieve old post data in view
 *    old_post('name');
 *
 */
function remember_post()
{
	$sess_name = \App\Config::get('old_post_sessname');
	if (!isset($_POST)){
		return false;
	}

	\Core\Session::set($sess_name, $_POST);
	return true;
}

function old_post($key, $default_value = "")
{
	$sess_name = \App\Config::get('old_post_sessname');
	if (!\Core\Session::exist($sess_name)){
		return $default_value;
	}

	return isset($_SESSION[$sess_name][$key])? $_SESSION[$sess_name][$key] : $default_value;
}

function has_remembered_post()
{
	$sess_name = \App\Config::get('old_post_sessname');

	return \Core\Session::exist($sess_name);
}

function forget_post()
{
	$sess_name = \App\Config::get('old_post_sessname');

	\Core\Session::delete($sess_name);
}