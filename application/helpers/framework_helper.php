<?php

function assets($resource = '') {return site_url("public/assets/{$resource}");}
function admin_url($resource = '') {return site_url("administrator/{$resource}");}
function echo_dump($data) {echo '<pre>'; var_dump($data); echo '</pre>';}
function echo_data($data) {echo '<pre>'; print_r($data); echo '</pre>';}

function view(string $page, array $data = [], bool $asText = false)
{
    $response = get_instance()->load->view($page, $data, $asText);
    if ($asText)
    {
        return $response;
    }

    return '';
}

/**
 * Helper Loader
 *
 * @param	string|string[]	$helpers	Helper name(s)
 * @return	void
 */
function helper($helpers)
{
    get_instance()->load->helper($helpers);
}

/**
 * Translate a line.
 *
 * @param string $line
 * @param array $args
 * @return string
 */
function __(string $line, $args = [])
{
    if ($line === '') {
        return $line;
    }

    $text = get_instance()->lang->line($line);

    if ($text === false) {
        $text = $line;
    }

    if (!empty($args))
    {
        return sprintf($text, ...$args);
    }

    return $text;
}

function csrf_field()
{
    $ci =& get_instance();
    return sprintf(
        "<input type='hidden' name='%s' value='%s'>",
        $ci->security->get_csrf_token_name(),
        $ci->security->get_csrf_hash()
    );
}

/**
 * Set flashdata
 *
 * Legacy CI_Session compatibility method
 *
 * @param	mixed	$key	Session data key or an associative array
 * @param	mixed	$value	Value to store
 * @return	void
 */
function setFlashData($key, $value)
{
    get_instance()->session->set_flashdata($key, $value);
}

function flash_data($key)
{
    return get_instance()->session->flashdata($key);
}

function printFlashData($key)
{
    if (flash_data($key))
    {
        return '<div class="alert alert-danger">' . flash_data($key) . '</div>';
    }
    return '';
}

function set_alert_floating($key, $value)
{
    get_instance()->session->set_flashdata($key, $value);
}
function print_alert_floating($key)
{
    if (flash_data($key))
    {
        return flash_data($key);
    }

    return '';
}

function fireToastrMessage($type, $key, $message, $title = 'Alerta')
{
    $value = \App\Helper\Toastr::{$type}($message, $title);
    get_instance()->session->set_flashdata($key, $value);
}

function registryNotification(string $type, string $message, string $title = 'Alerta')
{
    $session =& get_instance()->session;
    $value = \App\Helper\Toastr::{$type}($message, $title);
    $flashData = $session->flashdata('notifications');
    $flashData[] = $value;

    $session->set_flashdata('notifications', $flashData);
}

function dispatchNotifications()
{
    $str = '';
    $session =& get_instance()->session;
    $flashData = $session->flashdata('notifications');

    if ($flashData === '')
    {
        return $str;
    }

    foreach ($flashData as $data)
    {
        $str .= $data;
    }

    unset($_SESSION['notifications']);

    return $str;
}

function array_delete_keys(array $array, array $keys)
{
    $keysToDelete = [];
    foreach ($keys as $key)
    {
        $keysToDelete[$key] = '';
    }

    return array_diff_key($array, $keysToDelete);
}

function array_swap_data(array $arrayFrom, array $arrayTo, string $key)
{

}

function route(string $name, array $params = [])
{

}


function make_slug(string $text)
{
	helper('text');

	return url_title(convert_accented_characters($text), 'dash', true);
}


function getDiConfig(string $key): array
{
	$config = include(APPPATH . '/config/new_framework.php');

	if (!isset($config['di']))
	{
		return [];
	}

	return $config['di'][$key] ?? [];
}
