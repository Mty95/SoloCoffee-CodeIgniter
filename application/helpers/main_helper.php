<?php
/**
 * @param string $code
 * @param int $dim
 * @return string
 */
function flag_icon(string $code, int $dim = 32)
{
    return assets("img/flags/{$dim}/{$code}");
}

function skSpinner(string $message = '', string $id = '')
{
    if ($id !== '')
    {
        $id = "id=\"{$id}\"";
    }

    return '<div class="sk-spinner sk-spinner-wave"'.$id.'>
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                        <span class="sk-spinner-message">'.$message.'</span>
                    </div>';
}

function upload_url(string $url = ''): string
{
	return base_url('public/upload/' . $url);
}

function product_image(string $url = ''): string
{
	return upload_url($url);
}
