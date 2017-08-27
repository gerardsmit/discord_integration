<?php
/**
 * Discord integration
 * Author: Shinka
 * Copyright 2017 Shinka, All Rights Reserved
 *
 * License: http://www.mybb.com/about/license
 *
 */

// Disallow direct access to this file for security reasons
if (!defined('IN_MYBB')) {
    die('Direct initialization of this file is not allowed.');
}

$plugins->add_hook('datahandler_post_insert_thread_end', 'discord_integration_new_thread');
$plugins->add_hook('datahandler_post_insert_post_end', 'discord_integration_new_reply');

function discord_integration_info()
{
    global $lang;

    $lang->load('discord_integration');

    return array(
        'name' => $lang->discord_integration_name,
        'description' => $lang->discord_integration_desc,
        'author' => 'Shinka',
        'authorsite' => 'https://github.com/kalynrobinson/discord_integration',
        'website' => 'https://github.com/kalynrobinson/discord_integration',
        'version' => '2.1.1',
        'guid' => '',
        'codename' => 'discord_integration',
        'compatibility' => '18'
    );
}

function discord_integration_install()
{
    global $db, $lang;

    $lang->load('discord_integration');

    $setting_group = array(
        'name' => 'discord_integration',
        'title' => $lang->discord_integration_settings_title,
        'description' => $lang->discord_integration_settings_desc,
        'disporder' => 5,
        'isdefault' => 0
    );

    $gid = $db->insert_query('settinggroups', $setting_group);

    $setting_array = array(
        'discord_integration_new_thread_webhook' => array(
            'title' => $lang->discord_integration_new_thread_webhook_title,
            'description' => $lang->discord_integration_new_thread_webhook_desc,
            'optionscode' => 'text',
            'value' => '',
            'disporder' => 1
        ),
        'discord_integration_new_thread_forums' => array(
            'title' => $lang->discord_integration_new_thread_forums_title,
            'description' => $lang->discord_integration_new_thread_forums_desc,
            'optionscode' => 'forumselect',
            'value' => '',
            'disporder' => 2
        ),
        'discord_integration_new_thread_groups' => array(
            'title' => $lang->discord_integration_new_thread_groups_title,
            'description' => $lang->discord_integration_new_thread_groups_desc,
            'optionscode' => 'groupselect',
            'value' => '',
            'disporder' => 3
        ),
        'discord_integration_new_thread_users' => array(
            'title' => $lang->discord_integration_new_thread_users_title,
            'description' => $lang->discord_integration_new_thread_users_desc,
            'optionscode' => 'text',
            'value' => '',
            'disporder' => 4
        ),
        'discord_integration_new_thread_default_nickname' => array(
            'title' => $lang->discord_integration_new_thread_default_nickname_title,
            'description' => $lang->discord_integration_new_thread_default_nickname_desc,
            'optionscode' => 'yesno',
            'value' => 0,
            'disporder' => 5
        ),
        'discord_integration_new_thread_nickname' => array(
            'title' => $lang->discord_integration_new_thread_nickname_title,
            'description' => $lang->discord_integration_new_thread_nickname_desc,
            'optionscode' => 'text',
            'value' => '',
            'disporder' => 6
        ),
        'discord_integration_new_thread_message' => array(
            'title' => $lang->discord_integration_new_thread_message_title,
            'description' => $lang->discord_integration_new_thread_message_desc,
            'optionscode' => 'textarea',
            'value' => $lang->discord_integration_new_thread_message,
            'disporder' => 7
        ),
        'discord_integration_new_reply_webhook' => array(
            'title' => $lang->discord_integration_new_reply_webhook_title,
            'description' => $lang->discord_integration_new_reply_webhook_desc,
            'optionscode' => 'text',
            'value' => '',
            'disporder' => 8
        ),
        'discord_integration_new_reply_forums' => array(
            'title' => $lang->discord_integration_new_reply_forums_title,
            'description' => $lang->discord_integration_new_reply_forums_desc,
            'optionscode' => 'forumselect',
            'value' => '',
            'disporder' => 9
        ),
        'discord_integration_new_reply_groups' => array(
            'title' => $lang->discord_integration_new_reply_groups_title,
            'description' => $lang->discord_integration_new_reply_groups_desc,
            'optionscode' => 'groupselect',
            'value' => '',
            'disporder' => 10
        ),
        'discord_integration_new_reply_users' => array(
            'title' => $lang->discord_integration_new_reply_users_title,
            'description' => $lang->discord_integration_new_reply_users_desc,
            'optionscode' => 'text',
            'value' => '',
            'disporder' => 11
        ),
        'discord_integration_new_reply_default_nickname' => array(
            'title' => $lang->discord_integration_new_reply_default_nickname_title,
            'description' => $lang->discord_integration_new_reply_default_nickname_desc,
            'optionscode' => 'yesno',
            'value' => 0,
            'disporder' => 12
        ),
        'discord_integration_new_reply_nickname' => array(
            'title' => $lang->discord_integration_new_reply_nickname_title,
            'description' => $lang->discord_integration_new_reply_nickname_desc,
            'optionscode' => 'text',
            'value' => '',
            'disporder' => 13
        ),
        'discord_integration_new_reply_message' => array(
            'title' => $lang->discord_integration_new_reply_message_title,
            'description' => $lang->discord_integration_new_reply_message_desc,
            'optionscode' => 'textarea',
            'value' => $lang->discord_integration_new_reply_message,
            'disporder' => 14
        ),
        'discord_integration_additional' => array(
            'title' => $lang->discord_integration_additional_title,
            'description' => $lang->discord_integration_additional_desc,
            'optionscode' => 'textarea',
            'value' => $lang->discord_integration_additional_value,
            'disporder' => 15
        )
    );

    foreach ($setting_array as $name => $setting) {
        $setting['name'] = $name;
        $setting['gid'] = $gid;

        $db->insert_query('settings', $setting);
    }

    rebuild_settings();
}

function discord_integration_is_installed()
{
    global $settings;

    return isset($settings['discord_integration_new_thread_webhook']);
}

function discord_integration_uninstall()
{
    global $db;

    $db->delete_query('settings', "name LIKE 'discord_integration_%'");
    $db->delete_query('settinggroups', "name = 'discord_integration'");

    rebuild_settings();
}

function discord_integration_activate()
{
}

function discord_integration_deactivate()
{
}

function discord_integration_new_thread($handler)
{
    global $tid, $pid;

    if (!$tid) $tid = $handler->tid;
    if (!$pid) $pid = $handler->pid;

    discord_integration_send_general('new_thread');
    discord_integration_send_specific('new_thread');
}

function discord_integration_new_reply($handler)
{
    global $tid, $pid;

    if (!$pid) $pid = $handler->return_values['pid'];
    if (!$tid) $tid = $handler->data['tid'];

    discord_integration_send_general('new_reply');
    discord_integration_send_specific('new_reply');
}

function discord_integration_send_specific($behavior)
{
    $specifics = discord_integration_has_specific($behavior);
    if (!$specifics) return;

    foreach ($specifics as $specific) {
        discord_integration_send_request($behavior, $specific['webhook'], $specific['nickname'], $specific['message']);
    }
}

function discord_integration_send_general($behavior)
{
    global $mybb;

    $webhook = $mybb->settings['discord_integration_' . $behavior . '_webhook'];
    if (!$webhook || !discord_integration_has_permission($behavior)) return;

    discord_integration_send_request($behavior, $webhook);
}

function discord_integration_explode_alternatives()
{
    global $mybb;

    // Trim first '-' from settings
    $settings = substr($mybb->settings['discord_integration_additional'], 1);

    // For some reason, \n--\n doesn't work as a delimiter, so just trim extra whitespace.
    $exploded_settings = explode("\n---", $settings);
    $settings = array();

    foreach ($exploded_settings as $key => $value) {
        $explosion = explode("\n-", $value);
        $inner = array();
        foreach ($explosion as $ekey => $evalue) {
            $inner_explosion = explode('=', $evalue);
            $inner[strtolower(trim($inner_explosion[0]))] = trim($inner_explosion[1]);
        }
        array_push($settings, $inner);
    }

    return $settings;
}

function discord_integration_has_specific($behavior)
{
    $alternatives = discord_integration_explode_alternatives();
    $to_fulfill = array();
    foreach ($alternatives as $alt) {
        if (discord_integration_has_specific_permission($alt, $behavior)) array_push($to_fulfill, $alt);
    }

    return $to_fulfill;
}

function discord_integration_has_specific_permission($specific, $behavior)
{
    global $mybb, $thread;

    $allowed = true;

    if ($specific['behavior'] != $behavior) return false;

    if ($specific['forums']) {
        $allowed_forums = explode(',', $specific['forums']);
        $forum = $mybb->input['fid'];
        $allowed = in_array((string)$forum, $allowed_forums);
    }

    if ($specific['groups'] && $allowed) {
        $allowed_groups = explode(',', $specific['groups']);
        $user_groups = explode(',', $mybb->user['usergroup']);
        $allowed = count(array_intersect($user_groups, $allowed_groups)) > 0;
    }

    if ($specific['prefixes'] && $allowed) {
        $allowed_prefixes = explode(',', $specific['prefixes']);
        $prefix = $thread['prefix'];
        $allowed = in_array((string)$prefix, $allowed_prefixes);
    }

    if ($specific['users'] && $allowed) {
        $allowed_users = explode(',', $specific['users']);
        $user = $mybb->user['uid'];
        $allowed = in_array((string)$user, $allowed_users);
    }

    return $allowed;
}

function discord_integration_has_permission($behavior)
{
    global $mybb, $fid;

    $allowed = true;

    // Not all groups are allowed
    if ($mybb->settings['discord_integration_' . $behavior . '_groups'] != -1) {
        $user_groups = explode(',', $mybb->user['usergroup']);
        $allowed_groups = explode(',', $mybb->settings['discord_integration_' . $behavior . '_groups']);
        $allowed = count(array_intersect($user_groups, $allowed_groups)) > 0;
    }

    // Not all forums are allowed
    if ($mybb->settings['discord_integration_' . $behavior . '_forums'] != -1) {
        $allowed_forums = explode(',', $mybb->settings['discord_integration_' . $behavior . '_forums']);
        $forum = $fid;
        $allowed = in_array($forum, $allowed_forums);
    }

    // Not all users are allowed
    if ($mybb->settings['discord_integration_' . $behavior . '_users']) {
        $allowed_users = explode(',', $mybb->settings['discord_integration_' . $behavior . '_users']);
        $user = $mybb->user['uid'];
        $allowed = in_array($user, $allowed_users);
    }

    return $allowed;
}

function discord_integration_build_request($behavior, $nickname = NULL, $content = NULL)
{
    global $cache, $tid, $pid, $mybb, $forum;

    $SHORT_POST_LENGTH = 200;

    if (!$content) $content = $mybb->settings['discord_integration_' . $behavior . '_message'];

    $prefix = $cache->read("threadprefixes")[$mybb->input['threadprefix']]['prefix'];

    $userurl = "{$mybb->settings['bburl']}/member.php?action=profile&uid={$mybb->user['uid']}";
    $threadurl = "{$mybb->settings['bburl']}/showthread.php?tid={$tid}&pid={$pid}#pid{$pid}";
    $forumurl = "{$mybb->settings['bburl']}/forumdisplay.php?fid={$forum['fid']}";

    if ($mybb->user['username']) $username = $mybb->user['username'];
    else if ($mybb->input['username']) $username = $mybb->input['username'];
    else $username = 'A Guest';

    if ($mybb->user['uid'])
        $userlink = "[{$mybb->user['username']}]($userurl)";
    else
        $userlink = $username;

    $threadlink = "[{$mybb->input['subject']}]($threadurl)";
    $forumlink = "[{$forum['name']}]($forumurl)";

    // Remove quote
    $messageshort = preg_replace('/\[quote.*?\](.*?)\[\/quote\]/ism', '', $mybb->input['message']);

    // Truncate
    $messageshort = StringCutter::truncate($messageshort, $SHORT_POST_LENGTH, '...', array('word' => true, 'bbcode' => true));

    // Escape everyone and here
    $messageshort = preg_replace('/@(everyone|here)/', '__@__$1', $messageshort);

    // Styling
    $messageshort = preg_replace('/\[b](.*?)\[\/b\]/ism', '**$1**', $messageshort);
    $messageshort = preg_replace('/\[i](.*?)\[\/i\]/ism', '_$1_', $messageshort);
    $messageshort = preg_replace('/\[u](.*?)\[\/u\]/ism', '__$1__', $messageshort);
    $messageshort = preg_replace('/\[s](.*?)\[\/s\]/ism', '~~$1~~', $messageshort);

    // Remove bbcode
    $messageshort = preg_replace('/\[[^\]]+\]/', '', $messageshort);

    // Trim the message
    $messageshort = trim($messageshort);

    $request = new stdClass();

    if ($nickname) {
        try {
            eval('$request->username = "' . $nickname . '";');
        } catch (Exception $e) {
            $request->username = $nickname;
        }
    } else if (!$mybb->settings['discord_integration_' . $behavior . '_default_nickname']) {
        if ($mybb->settings['discord_integration_' . $behavior . '_nickname'])
            $request->username = $mybb->settings['discord_integration_' . $behavior . '_nickname'];
        else {
            $request->username = $mybb->user['username'];
            $request->avatar_url = $mybb->settings['bburl'] . $mybb->user['avatar'];
        }
    }

    try {
        eval('$content = "' . $content . '";');
    } catch (Exception $e) {
        $content = "Sorry, there's something wrong with your message variables!";
    }

    $request->content = $content;

    return $request;
}

function discord_integration_send_request($behavior, $webhook, $nickname = NULL, $message = NULL)
{
    $request = discord_integration_build_request($behavior, $nickname, $message);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $webhook,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => http_build_query($request, '', '&'),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    return $request;
}

if (!class_exists('StringCutter')) {
    /**
     * @file StringCutter.php
     * @author Moritz Kornher <mail@sweil.de>
     * @version 0.1
     *
     * @section DESCRIPTION
     *
     * This class provides static methods to cut strings with word, html
     * or bbcode awareness.
     *
     */
    class StringCutter
    {
        const HTML_STANDALONE = 'area|base|basefont|br|col|frame|hr|img|input|isindex|link|meta|param';
        const BBCODE_STANDALONE = 'attachment|\\*';

        /**
         * Like cut() but word, html, or bbcode aware and some other options
         *
         * @param string $text Text to be truncated
         * @param int $length Max length of text
         * @param string $extension Extend given string by this if truncated
         * @param array $awareness Setting awareness: (boolean) word, html, bbcode
         * @param array $options Options: (boolean) count_html, count_bbcode, below
         *
         * @return string Text truncated with awareness
         * */
        static public function truncate($text, $length, $extension, $awareness = array(), $options = array())
        {
            // check awarness & options
            if (!isset($awareness))
                $awareness = array();
            if (!isset($options))
                $options = array();
            if (!isset($awareness['word']))
                $awareness['word'] = false;
            if (!isset($awareness['html']))
                $awareness['html'] = false;
            if (!isset($awareness['bbcode']))
                $awareness['bbcode'] = false;
            if (!isset($options['count_html']))
                $options['count_html'] = false;
            if (!isset($options['count_bbcode']))
                $options['count_bbcode'] = false;
            if (!isset($options['below']))
                $options['below'] = true;
            // set count to false, if not html/bbcode aware
            $options['count_html'] = !$awareness['html'] || $options['count_html'];
            $options['count_bbcode'] = !$awareness['bbcode'] || $options['count_bbcode'];
            // Do we have to cut?
            if (strlen(StringCutter::strip($text, !$options['count_html'], !$options['count_bbcode'])) <= $length)
                return $text;
            // truncated html/bbcode aware?
            if (!($awareness['html'] || $awareness['bbcode'])) {
                return StringCutter::cut_wordaware($text, $length, $extension, $options['below']);
            }
            // create stripped text for text-only length
            $stripped_text = StringCutter::strip($text, $awareness['html'], $awareness['bbcode']);
            // Create function to get absolut length of splitted text, wordaware or not
            $calcLength_params = '$l, $t, $e="' . $extension . '", $w=' . ($awareness['word'] ? 'true' : 'false') . ', $b=' . ($options['below'] ? 'true' : 'false');
            $calcLength_code = 'return StringCutter::calc_truncated_length($t,$l,$e,$w,$b);';
            $calcLength = create_function($calcLength_params, $calcLength_code);
            $textonly_length = $calcLength($length, $stripped_text);
            // Split Text into parts with 0 or 1 html/bbcode-tag
            $html_regex = '(<\/?[\w]+[^>]*?>)?([^<]*)';
            $bbcode_regex = '(\[\/?[\w]+[^\]]*?\])?([^\[]*)';
            $html_regex = '(<\/?[\w]+[^>]*?>)?([^<]*)';
            $bbcode_regex = '(\[\/?[\w]+[^\]]*?\])?([^\[]*)';
            $regex = '((?:<\/?[\w]+[^>]*?>)|(?:\[\/?[\w\*]+[^\]]*?\]))?([^<\[]*)';
            $text_parts = array();
            preg_match_all("#$regex#s", $text, $text_parts, PREG_SET_ORDER);
            // init some data
            $tags_to_close = array();
            $tag_type = null;
            $compiled_text = '';
            $compiled_counter = 0;
            $htmlbbcode_length = 0;
            // now walk through text parts
            foreach ($text_parts as $part) {
                // save old length of html/bbcode contents
                $htmlbbcode_old_length = $htmlbbcode_length;
                // check for tags, so we may auto-close them later
                if (!empty($part[1])) {
                    // which type is the tag?
                    $bbtag = ('[' === substr($part[1], 0, 1));
                    // check if corresponding awarness is set
                    if (($bbtag && $awareness['bbcode']) || (!$bbtag && $awareness['html'])) {
                        // calculate real count
                        $count_tag = ($options['count_html'] && !$bbtag)
                            || ($options['count_bbcode'] && $bbtag);
                        // set some data depending on tag is html or bbcode
                        if ($bbtag) { // tag is bbcode
                            $standalone = '#(\[(' . StringCutter::BBCODE_STANDALONE . ')[^\]]*?\])#i';
                            $closing = '#\[\/([\w]+[^\]]*?)\]#';
                            $opening = '#\[([\w]+)[^\]]*?\]#';
                            list($closing_pre, $closing_post) = array('[/', ']');
                        } else { // tag is html
                            $standalone = '#(<(' . StringCutter::HTML_STANDALONE . ')[^>]*?>)#i';
                            $closing = '#<\/([\w]+[^>]*?)>#';
                            $opening = '#<([\w]+)[^>]*?>#';
                            list($closing_pre, $closing_post) = array('</', '>');
                        }
                        // init tag_match array
                        $tag_match = array();
                        // standalone-tag?
                        if (preg_match($standalone, $part[0], $tag_match)) {
                            // set tag type
                            $tag_type = 'self';
                            // count tag?
                            if ($count_tag) {
                                $htmlbbcode_length += strlen($part[1]);
                            }
                        } // closing tag?
                        elseif (preg_match($closing, $part[0], $tag_match)) {
                            // set tag type
                            $tag_type = "closing";
                            //remove from open tag list
                            $closing_tag = $closing_pre . $tag_match[1] . $closing_post;
                            if (false !== ($tag_index = array_search($closing_tag, $tags_to_close))) {
                                unset($tags_to_close[$tag_index]);
                            }
                            // unocunt previously counted closing tag
                            if ($count_tag) {
                                $htmlbbcode_length += strlen($part[1]);
                                $htmlbbcode_length -= strlen($closing_tag);
                            }
                        } // opening tag?
                        elseif (preg_match($opening, $part[0], $tag_match)) {
                            // set tag type
                            $tag_type = 'opening';
                            //add to $tags_to_close list
                            $closing_tag = $closing_pre . $tag_match[1] . $closing_post;
                            array_unshift($tags_to_close, $closing_tag);
                            // precount upcoming closing tag
                            if ($count_tag) {
                                $htmlbbcode_length += strlen($part[1] . $closing_tag);
                            }
                        }
                    }
                }
                // Recalculate textonly length if length of html/bbcode contents changed
                if ($htmlbbcode_old_length != $htmlbbcode_length)
                    $textonly_length = $calcLength($length - $htmlbbcode_length, $stripped_text);
                // string fits in?
                if ($compiled_counter + strlen($part[2]) <= $textonly_length) {
                    // add part to compiled text
                    $compiled_text .= $part[0];
                    // raise counter
                    $compiled_counter += strlen($part[2]);
                    // text is full, no need for another round
                    if ($compiled_counter >= $textonly_length) {
                        break;
                    }
                    // string doesnt fit => we have to cut!
                } else {
                    // calculate lenght of the rest string
                    $part_length = $textonly_length - strlen(StringCutter::strip($compiled_text, $awareness['html'], $awareness['bbcode']));
                    // Get rest of text if avaiable or if we want to stay above the border
                    if ($part_length >= 0 || !$options['below']) {
                        // truncate the text part
                        $part_length = ($part_length < 0) ? 0 : $part_length;
                        if ($awareness['word'])
                            $cutted_text = StringCutter::cut_wordaware($part[2], $part_length, '', $options['below']);
                        else
                            $cutted_text = StringCutter::cut($part[2], $part_length, '');
                        // concat truncated part to compiled text
                        $compiled_text .= $part[1] . rtrim($cutted_text, ' ');
                        // we dont want an empty tag
                    } elseif ($tag_type == 'opening') {
                        array_shift($tags_to_close);
                    }
                    // now text is full
                    break;
                }
            }
            // add all unclosed tags
            $compiled_text .= implode('', $tags_to_close);
            // remove space at end of text
            $compiled_text = rtrim($compiled_text, ' ');
            return $compiled_text . $extension;
        }

        /**
         * Cuts $text after $len chars and extends with $ext if truncated
         *
         * @param string $text Text to shorten
         * @param int $len Max length of text
         * @param string $ext Extends $text by this if truncated
         *
         * @return string Cutted text
         * */
        static public function cut($text, $len, $ext = '')
        {
            // Do we have to cut?
            if (strlen($text) > $len) {
                return substr($text, 0, $len - strlen($ext)) . $ext;
            }
            return $text;
        }

        /**
         * Like cut() but wordaware
         *
         * @param string $text Text to shorten
         * @param int $length Max length of text
         * @param string $extension Extends $text by this if truncated
         * @param bool $below Stay below the $len limit (true) or get closest result above the border (false)
         *
         * @return string Truncated text
         * */
        static public function cut_wordaware($text, $length, $extension = '', $below = true)
        {
            // remove space at end of text
            $text = rtrim($text, ' ');
            // Do we have to cut?
            if (strlen($text) > $length) {
                // Get relevant substring
                $sub = substr($text, 0, $length - strlen($extension) + 1);
                // stay below the border
                if ($below) {
                    // search for break from the end of string
                    $length = (int)strrpos($sub, ' ');
                    // Take the first break above the border
                } else {
                    $length = strpos($text, ' ', strlen($sub) - 1);
                    $length = ($length === false) ? strlen($text) : $length;
                }
                return substr($text, 0, $length) . $extension;
            }
            return $text;
        }

        /**
         * Strips bbcode from text
         *
         * @param string $str Text to be stripped
         * @param string $allowable_tags Comma separated string of allowable tags
         *
         * @return string BBCode free text
         * */
        static public function strip_bbcode($str, $allowable_tags = '')
        {
            $allowable_tags = array_map('strtolower', explode(',', $allowable_tags));
            // search for tags
            $matches = array();
            preg_match_all('#\[\/?([\w]+)[^\]]*?\]#', $str, $tags, PREG_PATTERN_ORDER);
            foreach ($tags[1] as $tag) {
                // is tag allowable?
                if (!in_array(strtolower($tag), $allowable_tags))
                    $str = preg_replace('#(\[\/?' . $tag . '[^\]]*?\])#i', '', $str);
            }
            return $str;
        }

        /**
         * Strips html and bbcode from text
         *
         * @param string $str Text to be stripped
         * @param string $html Strip HTML tags
         * @param bool $bbcode Strip BBCode tags
         *
         * @return string Stripped text
         * */
        static private function strip($str, $html, $bbcode)
        {
            if ($html)
                $str = strip_tags($str);
            if ($bbcode)
                $str = StringCutter::strip_bbcode($str);
            return $str;
        }

        /**
         * Calculates the new text length for any truncated text
         *
         * @param string $text Text to be truncated
         * @param int $length Max length of text
         * @param string $extension Extend given string by this if truncated
         * @param bool $word_awareness Setting word awareness.
         * @param bool $below Stay below the $len limit (true) or get closest result above the border (false)
         *
         * @return int Returns the length of truncated pure text
         * */
        static public function calc_truncated_length($text, $length, $extension, $word_awareness, $below)
        {
            // Truncate word aware?
            if ($word_awareness) {
                $text = StringCutter::cut_wordaware($text, $length, $extension, $below);
            } else {
                $text = StringCutter::cut($text, $length, $extension);
            }
            return strlen($text) - strlen($extension);
        }
    }
}