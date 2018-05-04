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

function genOptionHtmlTag($from, $to)
{
		$html = '';
			for ($i = $from; $i <= $to; $i++) { 
						$html .= '<option value="' . $i . '">' . $i . '</option>';
							}

			return $html;
}
