<?php

/**
 * @file
 * WordPress Plugin
 *
 * Plugin Name: WP-Domainer
 * Version: .03
 * Plugin URI: http://cuppster.com
 * Description: Domainer Features for Wordpress
 * Author: Jason Cupp
 * Author URI: http://cuppster.com
 */

add_shortcode('domains', 'shortcode_domains');

/**
* [domains] shortcode
*/
function shortcode_domains($atts, $content = '') {

  extract(shortcode_atts(array(
    'list' => true,				
    'case' => '',
    'protect'=> false,
    'link' => '',
    'text' => '',
    'newwindow' => false,
  ), $atts));

  $lines = explode ("\n",$content);
  $newcontent = "";
  
  if ($list) {
    $newcontent .= "<ul class='domains'>\n";
  }		

  foreach ($lines as $line) {
    
    if (preg_match("/^([0-9a-z][0-9a-z\-]+\.[a-z]{2,4})(.*)/i",$line,$match)) {
      
      if ('lower' == $case)
        $dn = strtolower($match[1]);
      elseif ('upper' == $case)
        $dn = strtoupper($match[1]);
      else
        $dn = $match[1]; 	

      $after = $match[2];
      
      if ($list) {
        $newcontent .= "<li>";
      }
      
      $dnob = dn_obfuscate($dn);
      
      // anchor text templates
      
      $anchortext = "";
      if (!empty($text)) {
        // replace %DN% with domain name
        $anchortext = preg_replace("|%dn%|i",$dn, $text);
        $anchortext = preg_replace("|%dnob%|i",$dnob, $anchortext);
        
      }
      else {					
        if ('true' == $protect)
          $anchortext = "$dnob";
        else
          $anchortext = "$dn";
      }
      
      // link templates
      
      $linkhref = false;
      if (!empty($link)) {
        $linkhref = preg_replace("|%dn%|i",$dn, $link);					
      }
      
      if ($linkhref) {
        $newwindow = $newwindow ? "target=\"_blank\"" : "";
        $newcontent .= "<a $newwindow href=\"$linkhref\">$anchortext</a>$after";
      }
      else {
        $newcontent .= $anchortext.$after;
      }
      
      if ($list) {
        $newcontent .= "</li>";					
      }
        
    }
  }
  
  if ($list) {
    $newcontent .= "</ul>\n";
  }
  
  return $newcontent ;
}

/**
 * obfuscate a domain name
 */
function dn_obfuscate($text, $replacedot = '[dot]') {
	return preg_replace ("/\.([com|net|org|biz|info|us])/i","<span style='color: gray'>$replacedot</span>$1",$text);
}

?>
