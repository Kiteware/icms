<?php
namespace Nixhatter\ICMS\controller;
/**
 * Controller
 *
 * @package ICMS
 * @author Dillon Aykac
 */

defined('_ICMS') or die;

use Respect\Validation\Validator as v;

class Controller {
    protected $model;
    public $user_id;
    public $page;
    public $data;
    protected $settings;
    protected $errors = [];

    public function __construct(\Nixhatter\ICMS\model\UserModel $model) {
        $this->model = $model;
        $this->settings = $model->container['settings'];
        $this->page = "";
    }

    public function success() {
        echo ("<script>window.onload = function() {
                    successAlert('');
               };</script>");
    }
    public function alert($type, $message) {
        echo("<script>window.onload = function() {
               ".$type."Alert('".$message."');
              };</script>");
    }
    public function logged_in()
    {
        if (isset($_SESSION['id'])) {
            $this->user_id = $_SESSION['id'];
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * View generation for blog posts
     * @param $posts - An array of blog posts
     * @param int $amount - How many posts to show
     * @return string - Fully formatted html of the blog posts
     */
    protected function compilePosts($posts, $amount = 10) {
        $blogArray = "";
        if (count($posts) === 1) {
            // View one blog post
            $content = $posts[0]['post_content'];
            $blogArray .= '<h1 class="title"> '.$posts[0]["post_title"].'</h1>
                    <p class="text-muted">'.date('F j, Y', strtotime($posts[0]['post_date'])) .'</p>
                    <p>'.$content.'</p>
                    <hr />
                    <p class="text-mute">Written By ' . $posts[0]['post_author'] . '</p>';
        } elseif (empty($posts)) {
            // No Blog Posts
            $blogArray .= '<p> There are currently no blog posts.</p>';
        } else {
            // Multiple Blog Posts
            $i=0;
            foreach ($posts as $post) {
                $i++;
                if($i>$amount) break;
                $content = $post['post_content'];
                $blogArray .= '<div class="row"><h1><a href="/blog/view/' . urlencode($post['post_id']) . '">' . htmlspecialchars($post['post_title']) . '</a></h1>
                        <p class="text-muted">' . date('F j, Y', strtotime($post['post_date'])) . '</p>';
                $blogArray .=  $this->truncateHTML($content);
                $blogArray .= '<strong><a class="btn btn-secondary pull-right" href="/blog/view/' . urlencode($post['post_id']) . '">Read more</a></strong>
                        </div><hr />';
            }
        }
        return $blogArray;
    }

    protected function compileFrontPagePosts($posts, $amount = 10) {
        $blogArray = "";
        if (count($posts) === 1) {
            // View one blog post
            $content = $posts[0]['post_content'];
            $blogArray .= '<h1 class="title"> '.$posts[0]["post_title"].'</h1>
                    <p class="text-muted">'.date('F j, Y', strtotime($posts[0]['post_date'])) .'</p>
                    <p>'.$content.'</p>
                    <p class="text-mute">Written By ' . $posts[0]['post_author'] . '</p>';
        } elseif (empty($posts)) {
            // No Blog Posts
            $blogArray .= '<p> There are currently no blog posts.</p>';
        } else {
            // Multiple Blog Posts
            $i=0;
            foreach ($posts as $post) {
                $i++;
                if($i>$amount) break;
                $content = $post['post_content'];
                $blogArray .= '<div class="row"><h1><a href="/blog/view/' . urlencode($post['post_id']) . '">' . htmlspecialchars($post['post_title']) . '</a></h1>
                        <p class="text-muted">' . date('F j, Y', strtotime($post['post_date'])) . '</p>';
                preg_match('/(<img .*?>)/', $content, $img_tag);
                if (isset($img_tag[1])) {
                    $pieces = explode($img_tag[1], $content);
                    $blogArray .= "<div class=\"col-xs-12 col-sm-3 no-padding-left news text-center\">". $img_tag[1] . "</div>";
                    $blogArray .= "<div class=\"col-xs-12 col-sm-9\">". $this->truncateHTML($pieces[1]) . "</div>";
                } else {
                    $blogArray .=  $this->truncateHTML($content);
                }
                $blogArray .= '<strong><a class="btn btn-secondary pull-right" href="/blog/view/' . urlencode($post['post_id']) . '">Read more</a></strong>
                        </div><hr />';
            }
        }
        return $blogArray;
    }

    public function inputValidation($variable, $option = null) {
        $variable = trim($variable);
        if (!empty($variable) && $variable != false) {
            $safe = htmlspecialchars($variable, ENT_QUOTES, "UTF-8");
            switch ($option) {
                case 'strict':
                    if (!v::alnum()->noWhitespace()->validate($variable)) {
                        $this->errors[] = $safe . " is not valid.";
                        $safe = "";
                    }
                    break;
                case 'int':
                    if(!v::intVal()->validate($variable)) {
                        $this->errors[] = $safe . " is not valid number.";
                        $safe = "";
                    }
                    break;
                case 'alpha':
                    if(!v::alpha()->validate($variable)) {
                        $this->errors[] = $safe . " can only include letters";
                        $safe = "";
                    }
                    break;
                /**
                 * Numbers, Letters, Forward slashes and one . in the middle
                 * True: hello.php, /dir/index2.html
                 * False: ../test.php, /dir/../../etc/passwd
                 * @param $variable
                 */
                case 'file':
                    if(!v::regex('@^[a-zA-Z/]+(\.{1}[a-zA-Z]+)?$@')->validate($variable)) {
                        $this->errors[] = $safe . " is not a valid file.";
                        $safe = "";
                    }
                    break;
            }
        } else {
            $safe = "";
            // $this->errors[] = "Missing input";
        }

        return $safe;

    }

    // Creates a preview of the text
    public function truncate($string,$append="&hellip;",$length=300) {
        $trimmed_string = trim($string);
        $new_length = $length;
        if (strlen($trimmed_string) < $new_length) $new_length = strlen($trimmed_string) - 50;
        $pos = strpos($trimmed_string, ' ', $new_length);
        return substr($trimmed_string,0,$pos )."<br />".$append;
    }

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     *
     * @param string $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param string $ending Ending to be appended to the trimmed string.
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param boolean $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    function truncateHtml($text, $ending = '...', $length = 300, $exact = false, $considerHtml = true) {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }
}