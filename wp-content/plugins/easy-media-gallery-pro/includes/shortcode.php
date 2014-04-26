<?php 

global $theopt;

function easy_media_shortcode( $atts ) {

if ( easy_get_option( 'easymedia_disen_plug' ) == '1' ) {

	  extract( shortcode_atts( array(
      'cat' => -1,
	  'col' => '',
	  'size' => '',
	  'align' => '',
	  'mark' => '',
	  'style' => '',		  
	  'med' => -1
   ), $atts ) );	
   
   ob_start();	
   
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // for pagination
echo '<script>var fodr = []; fodr[0] = "*"; </script>';

if ( $med <= '0' && $cat > '0' ) {
	
$emgargs = array( 
    'post_type' => 'easymediagallery',
    'showposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
    'order' => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'emediagallery',
            'terms' => $cat,
            'field' => 'term_id',
        )
    ),
);	
}

else if ( $cat <= '0' && $med > '0' ) {
	$fnlid = explode(",", $med);
	
	$emgargs = array(
	'post__in' => $fnlid, 
	'post_type' => 'easymediagallery',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'menu_order',
  	'paged' => $paged
	);
	}

$deff_img_limit = easy_get_option( 'easymedia_img_size_limit' ); // get the default image size limit
$theopt = easy_get_option( 'easymedia_frm_size' );
$showbadge = easy_get_option( 'easymedia_disen_showcntrthumb' ); 

// Custom columns filter	
if ( $col > 0 ) {
	$num_cols = $col; // set the number of columns here
	} else {
		$num_cols = easy_get_option( 'easymedia_columns' ); // set the number of columns here	
	}
	
// Custom columns Align	
if ( $align != '' ) {
	$cus_align = $align;
	} else {
		$cus_align = strtolower( easy_get_option( 'easymedia_alignstyle' ) ); // set media align	
	}
	
// Custom Style		
if ( $style != '' ) {
	if ( easy_get_option( 'easymedia_disen_style_man' ) == '1' ) {
	$cus_style = ucfirst( $style );
		} else { $cus_style = easy_get_option( 'easymedia_box_style' ); }
	} else {
		$cus_style = easy_get_option( 'easymedia_box_style' );
	}		

// Custom size filter	
	if ( $size != '' ) {
		$sizeval = explode(",", $size);
			if ( $sizeval[0] > 0 && $sizeval[1] > 0 && is_numeric( $sizeval[0] ) && is_numeric( $sizeval[1] ) ) { 
				$imwidth = $sizeval[0];
				$imheight = $sizeval[1];
			} else {
				$imwidth = stripslashes( $theopt['width'] );
				$imheight = stripslashes( $theopt['height'] );
				}	
			}
	else {
		$imwidth = stripslashes( $theopt['width'] );
		$imheight = stripslashes( $theopt['height'] );
	}
	
$emg_query = new WP_Query( $emgargs );
if ( $emg_query->have_posts() ):
$mediauniqueid = RandomString(6); //Random class for fitText
 
/*
echo'<script type="text/javascript">
	(function($,undefined){
	 $(document).ready(function() {
	$(".'.$mediauniqueid.'").fitText(1.1,{ maxFontSize: "12px" });
	});
	    })(jQuery);
		</script>'; */
		
echo '<div class="pagwrap" id="emgsing"><div id="alignstyle" class="easymedia_'.$cus_align.'">';
  for ( $i=1 ; $i <= $num_cols; $i++ ) :
    echo '<div id="col-'.$i.'" class="thecol">';
    $counter = $num_cols + 1 - $i;
	
	while ( $emg_query->have_posts() ) : $emg_query->the_post();

		//$image = wp_get_attachment_url( get_post_thumbnail_id( get_the_id() ) );
		//$image = get_the_post_thumbnail( get_the_id(), 'work-admin-thumb' ) ;
		$image = get_post_meta( get_the_id(), 'easmedia_metabox_img', true );
		$mediattl = esc_html( esc_js( get_post_meta( get_the_id(), 'easmedia_metabox_title', true ) ) ); $mediattl = stripslashes($mediattl);			
		$mediatype = get_post_meta( get_the_id(), 'easmedia_metabox_media_type', true );
		$isvidsize = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size', true );
		$ismapsize = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap_size', true );
		$galleryid = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_id', true );	
		$isresize = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_opt1', true );
		$isresize1 = get_post_meta( get_the_id(), 'easmedia_metabox_media_image_opt1', true );
		$usegalleryinfo = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_opt2', true );
		$link_type = get_post_meta( get_the_id(), 'easmedia_metabox_media_link_opt1', true );
		$thepostid = get_the_id();
		
			if ( $image == '' ) {
				$image = plugins_url( 'images/no-image-available.jpg' , __FILE__ ) ;
				}
				else {
					$image = $image;
					}

		switch ( $mediatype ) {
			case 'Single Image':
				
				if ( basename( $image ) == 'no-image-available.jpg' ) {
					$medialink = $image;
				}
					else {
				$attid = wp_get_attachment_image_src( emg_get_attachment_id_from_src( $image ), 'full' );
				$medialink = easymedia_imgresize( $attid[0], $deff_img_limit, $isresize1, $attid[1], $attid[2] );
				$medialink = explode(",", $medialink); $medialink = $medialink[0];
					}
					if ( $mark ) {
				$therell = "easymedia[" .$mark."]";
				} else {
					$therell = "easymedia";
					}
				
				
	    	break;
			
			case 'Multiple Images (Slider)':
				$therell = "easymedia[".$galleryid."]";
				$images = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery', true );
				
			ob_start();
				if ( is_array( $images ) ) {
					$ig = 0;
					echo '<div id="easymedia_gallerycontainer-'.$mediauniqueid.'" style="display:none">';
					foreach( $images as $img_id ) {
						
							//Changelog version 1.3.1.3 => Set 1st Image Gallery
							if($ig++ == 0) {
								$img = wp_get_attachment_image_src($img_id, 'full');
								$frstimg = $img_id;
								$medialink = easymedia_imgresize( $img[0], $deff_img_limit, $isresize, $img[1], $img[2] );
								$medialink = explode(",", $medialink); $medialink = $medialink[0];
								}
																
						$img = wp_get_attachment_image_src($img_id, 'full');
						$img_url = easymedia_imgresize( $img[0], $deff_img_limit, $isresize, $img[1], $img[2] );
                        $img_url = explode(",", $img_url); ?>
                	<a class="<?php echo $thepostid; ?>-<?php echo $img_id; ?>" href="<?php echo $img_url[0]; ?>" rel="<?php echo $therell; ?>"></a>
            		<?php
					$imgcount = $ig;
				} echo '</div>'; }
				else {
				echo '<div style="display:none"></div>';
				}
		$galle = ob_get_clean();
		if ($imgcount <= 1) {$sorn =  __( 'image', 'easmedia' );} else {$sorn = __( 'images', 'easmedia' );}

			break;			
			
			case 'Video':
				$vidcover = get_post_meta( get_the_id(), 'easmedia_metabox_img', true );
				$vidlink1 = get_post_meta( get_the_id(), 'easmedia_metabox_media_video', true );
				$vidlink2 = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_webm', true );
				$vidlink3 = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_ogg', true );

				if ( $vidlink1 != '' ) { $vidlink1 = $vidlink1;} else {$vidlink1 = '-';}
				if ( $vidlink2 != '' ) { $vidlink2 = $vidlink2;} else {$vidlink2 = '-';}
				if ( $vidlink3 != '' ) { $vidlink3 = $vidlink3;} else {$vidlink3 = '-';}
				if ( $vidcover != '' ) { $vidcover = $vidcover;} else {$vidcover = '-';}
				
				
	
				if ( pathinfo($vidlink1, PATHINFO_EXTENSION) == 'mp4' || pathinfo($vidlink2, PATHINFO_EXTENSION) == 'webm' || pathinfo($vidlink3, PATHINFO_EXTENSION) == 'ogv' || pathinfo($vidlink1, PATHINFO_EXTENSION) == 'wmv') {
					$medialink = $vidlink1.'#emg#'.$vidlink2.'#emg#'.$vidlink3.'#emg#'.emg_replace_extension($vidcover); }
					else {
						$medialink = $vidlink1; }

				
		if ( $mediatype == 'Video' && $isvidsize == 'off' ) {
			$cusvidw = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size_vidw', true );
			$cusvidh = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size_vidh', true );
			$therell = "easymedia[".$cusvidw." " .$cusvidh."]";
			}
		elseif ( $mediatype == 'Video' && $isvidsize == 'on' ) {
			$getarry = easy_get_option( 'easymedia_vid_size' );
			$defvidw = stripslashes( $getarry['width'] );
			$defvidh = stripslashes( $getarry['height'] );
			$therell = "easymedia[".$defvidw." " .$defvidh."]";
			}
		else {
			$therell = "easymedia";
			}				

	        break;
			
			case 'Google Maps':
				$medialink = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap', true ) ."&amp;output=embed";
				
		if ( $mediatype == 'Google Maps' && $ismapsize == 'off' ) {
			$cusgmw = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap_size_gmidw', true );
			$cusgmh = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap_size_gmidh', true );
			$therell = "easymedia[".$cusgmw." " .$cusgmh."]";
			}
		elseif ( $mediatype == 'Google Maps' && $ismapsize == 'on' ) {
			$getarry = easy_get_option( 'easymedia_gmap_size' );
			$defgmw = stripslashes( $getarry['width'] );
			$defgmh = stripslashes( $getarry['height'] );
			$therell = "easymedia[".$defgmw." " .$defgmh."]";
			}
		else {
			$therell = "easymedia";
			}					
				
	        break;			
			
			case 'Audio':
			$curaudiosource = get_post_meta(get_the_id(), 'easmedia_metabox_media_audio_source', true);
			$medialinktmp = get_post_meta( get_the_id(), 'easmedia_metabox_media_audio', true );
			$medialink = get_post_meta( get_the_id(), 'easmedia_metabox_media_audio', true );
							
				
					if ( $mark ) {
				$therell = "easymedia[" .$mark."]";
				} else {
					$therell = "easymedia";
					}
					
					if ( $curaudiosource == 'soundcloud.com' ) {
				$therell = "easymedia[600 170]";
				} else {

					$therell = "easymedia";
					}					
					
				
	        break;			
			
			case 'Link':
				$media_link = get_post_meta( get_the_id(), 'easmedia_metabox_media_link', true );	
				if ( $media_link !='' ) {
					if ( substr( $media_link, 0, 4 ) === 'http' || substr( $media_link, 0, 5 ) === 'https' ) {
						$media_link_fin = $media_link; 
						}
						else {
							$media_link_fin = 'http://' .$media_link; 
						}
					}
					else {
					$media_link_fin = $post->guid;
					}
					
				$medialink = $media_link_fin;
				$therell = "";
	        break;		
		}
		
      if( $counter%$num_cols == 0 ) :
	  
	  $emgthumbimg = emg_thumb_src( $image, $imwidth, $imheight, '0', '0' );
	  
	  	  	$curimgnmane = basename($image);
			if ( $curimgnmane == 'no-image-available.jpg' ) {
				$emgthumbimg = $image;
				} else {
					$emgthumbimg = $emgthumbimg;
					}	  
					
		if ( $mediatype == 'Video' && get_post_meta(get_the_id(), 'easmedia_metabox_media_video_fetchurl', true) != '' ) {
			$emgthumbimg = get_post_meta(get_the_id(), 'easmedia_metabox_media_video_fetchurl', true);			
		}	
		
		if ( $showbadge == '1' && $mediatype == 'Multiple Images (Slider)' ){
			$addbadge = '<span class="emg-badges"><span class="icount">'.$imgcount.'</span><span class="imgtg">'.$sorn.'</span></span>';
		} else {$addbadge = '';}						
	  
	  if ( easy_get_option( 'easymedia_disen_hovstyle' ) == '1' ) { ?>
     <div style="width:<?php echo $imwidth; ?>px; height:<?php echo $imheight; ?>px;" class="view da-thumbs preloaderview"><?php echo $addbadge; ?><div class="iehand"><img data-original="<?php echo $emgthumbimg; ?>"/><a onclick="easyActiveStyleSheet('<?php echo $cus_style; ?>');return true;" class="<?php if ( $mediatype == 'Multiple Images (Slider)' && $usegalleryinfo == 'on' ) { echo $thepostid.'-'.$frstimg; } else { echo $thepostid; } ?>" rel="<?php echo $therell; ?>" href="<?php echo $medialink; ?>" <?php if ( $link_type == 'on' && $mediatype == 'Link' ) { echo 'target="_blank"'; } ?>><article class="da-animate da-slideFromRight"><p <?php if ( $mediattl == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext"><?php echo $mediattl; ?></p><div class="forspan"><span class="zoom"></span></div></article></a></div></div>
            
<?php } elseif ( easy_get_option( 'easymedia_disen_hovstyle' ) == '' ) { ?>
<div class="view da-thumbs preloaderview" style="width:<?php echo $imwidth; ?>px; height:<?php echo $imheight; ?>px;"><?php echo $addbadge; ?><div class="iehand"><a onclick="easyActiveStyleSheet('<?php echo $cus_style; ?>');return true;" class="<?php if ( $mediatype == 'Multiple Images (Slider)' && $usegalleryinfo == 'on' ) { echo $thepostid.'-'.$frstimg; } else { echo $thepostid; } ?>" rel="<?php echo $therell; ?>" href="<?php echo $medialink; ?>" <?php if ( $link_type == 'on' && $mediatype == 'Link' ) { echo 'target="_blank"'; } ?>><img data-original="<?php echo $emgthumbimg; ?>"/><p <?php if ( $mediattl == '' ) { echo 'style="display:none !important;"'; } ?> class="da-animatenh emgfittext" style="display:none;"><?php echo $mediattl; ?></p><div class="forspana"><span class="zooma"></span></div></a></div></div>
<?php	}

		//Changelog version 1.0.1.0 => Generate Image Gallery
		if ( $mediatype == 'Multiple Images (Slider)' ) {
			echo $galle;
		}

	  endif;
      $counter++;
    endwhile;
   // rewind_posts();
    echo '</div>'; //closes the column div
  endfor;
  //next_posts_link('&laquo; Older Entries');
  //previous_posts_link('Newer Entries &raquo;');
else:
  echo '<div class="pfwrpr"><div class="alignstyle"><div class="thecol">'; ?>
  <div class="view"><img src="<?php echo plugins_url('images/ajax-loader.gif' , __FILE__); ?>" width="32" height="32"/></div>
  
  <?php
endif;
wp_reset_postdata();
echo '<div style="clear:both;"></div>';
echo '</div></div>';
		
$content = ob_get_clean();
return $content;

}
else {
ob_start();	
echo '<div style="display: none;"></div>';	
$content = ob_get_clean();
return $content;
	}

}

add_shortcode( 'easy-media', 'easy_media_shortcode' );


function easy_media_gnl_shortcode( $attsn ) {

if ( easy_get_option( 'easymedia_disen_plug' ) == '1' ) {
	extract( shortcode_atts( array(
	'med' => -1,
	'style' => '',
	'filter' => '',
	'pag' => '',	
	'def' => '',	
	'size' => ''
	), $attsn ) );
	
	ob_start();
	
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // for pagination
	
$deff_img_limit = easy_get_option( 'easymedia_img_size_limit' ); // get the default image size limit
$theopt = easy_get_option( 'easymedia_frm_size' ); 

// Custom Filter	
if ( $def != '' ) {
	echo '<script>var fodr = []; fodr[0] = ".fltr'.$def.'"; </script>';
	$clssltdall = 'class=""';
	} else {
		$def = '*';
		echo '<script>var fodr = []; fodr[0] = "'.$def.'"; </script>';
		$clssltdall = 'class="selected"';
		}

// Custom Style		
if ( $style != '' ) {
	if ( easy_get_option( 'easymedia_disen_style_man' ) == '1' ) {
	$cus_style = ucfirst( $style );
		} else { $cus_style = easy_get_option( 'easymedia_box_style' ); }
	} else {
		$cus_style = easy_get_option( 'easymedia_box_style' );
	}
	
// Custom size filter	
	if ( $size != '' ) {
		$sizeval = explode(",", $size);
			if ( $sizeval[0] > 0 && $sizeval[1] > 0 && is_numeric( $sizeval[0] ) && is_numeric( $sizeval[1] ) ) { 
				$imwidth = $sizeval[0];
				$imheight = $sizeval[1];
			} else {
				$imwidth = stripslashes( $theopt['width'] );
				$imheight = stripslashes( $theopt['height'] );
				}	
			}
	else {
		$imwidth = stripslashes( $theopt['width'] );
		$imheight = stripslashes( $theopt['height'] );
	}   

if ( $med > '0' ) {
	$finid = explode(",", $med);
	$medinarr = $finid;

	$emargs = array(
	'post__in' => $finid, 
	'post_type' => 'easymediagallery',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'menu_order',
  	'paged' => $paged
	);
	}   

if ( $med == '-1' ) {

global $post;
$args = array(
	'post_type' => 'easymediagallery',
	'order' => 'ASC',
  	'post_status' => 'publish',
  	'posts_per_page' => -1,
	'meta_query' => array(
		array(
			'key' => 'easmedia_metabox_media_type',
			'value' => 'Multiple Images (Slider)',
			'compare' => '='
		),
	)
 );
 
$myposts = get_posts( $args );
foreach( $myposts as $post ) :	setup_postdata($post);
		$array_id[] = $post->ID;
	endforeach;

if ($array_id) {
$finid = implode(",", $array_id); $finid = explode(",", $finid); $medinarr = $finid;
} else { return false;}

	$emargs = array(
	'post__in' => $finid,
	'post_type' => 'easymediagallery',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'menu_order',
  	'paged' => $paged
	);
} 
 
$emg_query = new WP_Query( $emargs );

if ( $emg_query->have_posts() ):
$mediauniqueid = RandomString(6); //Random class for fitText

	if ( $filter != '' && $pag == '' ) { 
        echo'<section id="emgoptions" class="emgclearfix"><ul id="filters" class="portfolio-tabs emgoption-set emgclearfix" data-option-key="filter">';
        echo'<li><a href="#filter" data-option-value="*" '.$clssltdall.' id="emgshowall">' . __( 'Show All', 'easmedia' ) . '</a></li>';
		
		foreach( $medinarr as $eachmed ) {
		$medttl1 = get_post_meta( $eachmed, 'easmedia_metabox_title', true );
		$medttl2 = get_the_title( $eachmed );
		
			if ( $def == $eachmed) {
				$clssdeffil = 'class="selected"';
				} else { $clssdeffil = 'class=""'; }		
		
		if ( $medttl1 != '' & $medttl2 == '' ) {echo'<li><a href="#filter" '.$clssdeffil.' data-option-value=".fltr'.$eachmed.'">'.$medttl1.'</a></li>';}
		if ( $medttl1 == '' & $medttl2 != '' ) {echo'<li><a href="#filter" '.$clssdeffil.' data-option-value=".fltr'.$eachmed.'">'.$medttl2.'</a></li>';}
		if ( $medttl1 != '' & $medttl2 != '' ) {echo'<li><a href="#filter" '.$clssdeffil.' data-option-value=".fltr'.$eachmed.'">'.$medttl1.'</a></li>';}				
		if ( $medttl1 == '' & $medttl2 == '' ) {echo'<li><a href="#filter" '.$clssdeffil.' data-option-value=".fltruntitled">Untitled</a></li>';}}
	
      echo'</ul></section>';
}

if ( $pag != '' ) { 
echo '<div class="pagwrap" id="'.$pag .'"><div id="pag-legend2" style="display:none;"></div><div class="emgpagntn easymedia_center emgclearfix">';
} else {
echo '<div class="emgajxloader"></div><div style="display: none;" class="pagwrap" id="nopagination"><div class="easycontainer easymedia_center emgclearfix">';	
}

while ( $emg_query->have_posts() ) : $emg_query->the_post();


if ( $pag != '' ) {
	$therell = "easymedia[".$mediauniqueid."]";
	$theclass = 'peasyitem';
	} else {
		//$therell = "easymedia[".$galleryid."]";
		$therell = "easymedia[showall]";
		$theclass = 'easyitem';
		}
		
		$galleryid = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_id', true );
		$images = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery', true );
		$isresize = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_opt1', true );
		$mttl1 = get_post_meta( get_the_id(), 'easmedia_metabox_title', true );
		$mttl2 = get_the_title();

		if ( $mttl1 !='' && $mttl2 =='' ) { $mediaid = get_the_id();}
		if ( $mttl1 =='' && $mttl2 !='' ) { $mediaid = get_the_id();} 
		if ( $mttl1 !='' && $mttl2 !='' ) { $mediaid = get_the_id();}  
		if ( $mttl1 =='' && $mttl2 =='' ) { $mediaid = 'untitled';}

				if ( is_array( $images ) ) {
					$ig = 0;
					foreach( $images as $img_id ) {
						
						$img = wp_get_attachment_image_src($img_id, 'full');
						$img_url = easymedia_imgresize( $img[0], $deff_img_limit, $isresize, $img[1], $img[2] );
                        $img_url = explode(",", $img_url);
						$img_info = get_post( $img_id );
						//$thumbttl = $img_info->post_title;
						//$thumbttl = esc_html( esc_js( $thumbttl ) );
						$ext = pathinfo($img[0], PATHINFO_EXTENSION);
						$filenm = basename($img[0], ".".$ext);		
						
						$usegalleryinfo = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_opt2', true );		
						if ( $usegalleryinfo == 'on' ) {
						$thumbttl = $img_info->post_title;
						$thumbttl = esc_html( esc_js( $thumbttl ) );
						} else {
						$thumbttl = get_post_meta( get_the_id(), 'easmedia_metabox_title', true );
						}						
						
						$emgthumbimg = emg_thumb_src( $img[0], $imwidth, $imheight, $img[1], $img[2] );	
						$thumbttl = stripslashes($thumbttl);			

if ( easy_get_option( 'easymedia_disen_hovstyle' ) == '1' ) {						
 echo'<div style="width:'.$imwidth.'px; height:'.$imheight.'px;" class="'.$theclass.' view da-thumbs preloaderview fltr'.$mediaid.'"><div class="iehand"><img data-original="'.$emgthumbimg.'" alt="'.$filenm.'" /><a onclick="easyActiveStyleSheet(\''.$cus_style.'\');return true;" class="'.get_the_id().'-'.$img_id.'" rel="'.$therell.'" href="'.$img_url[0].'"><article class="da-animate da-slideFromRight"><p class="emgfittext">'.$thumbttl.'</p><div class="forspan"><span class="zoom"></span></div></article></a></div></div>'; } 
 
elseif ( easy_get_option( 'easymedia_disen_hovstyle' ) == '' ) {
	$nottl = 'style="display:none !important;"';
 echo'<div style="width:'.$imwidth.'px; height:'.$imheight.'px;" class="'.$theclass.' view da-thumbs preloaderview fltr'.$mediaid.'"><div class="iehand"><a onclick="easyActiveStyleSheet(\''.$cus_style.'\');return true;" class="'.get_the_id().'-'.$img_id.'" rel="'.$therell.'" href="'.$img_url[0].'"><img data-original="'.$emgthumbimg.'" alt="'.$filenm.'" /><p '. ( ($thumbttl == "") ? $nottl  : "") .' class="da-animatenh emgfittext" style="display:none;">'.$thumbttl.'</p><div class="forspana"><span class="zooma"></span></div></a></div></div>'; 	
}
				}  }
				else {
				echo '<div style="display:none"></div>';
				}

?>

<?php
endwhile;
else:
echo '<div class="easymedia_center">'; 
echo '<div class="view"><img src="'.plugins_url('images/ajax-loader.gif' , __FILE__).'" width="32" height="32"/></div>';	
$contnt = ob_get_clean();
return $contnt;  

endif;
wp_reset_postdata();
echo '<div style="clear:both;"></div>';

if ( $pag != '' ) { 
echo '</div><div class="emg-pag-holder"></div></div>';
} else {
echo '</div></div>';

echo'<script type="text/javascript">
jQuery(document).ready(function() {
		 jQuery( "#filters li a" ).click(function() {
			 elmntsel = jQuery(this).attr("data-option-value");
			 if (elmntsel == "*") {
				jQuery(".iehand a").attr("rel", "easymedia[showall]");
			 } else {
				 jQuery("."+elmntsel.substring(1)+" .iehand a").attr("rel", "easymedia["+elmntsel+"]");
				 }
			 });
		});
		</script>';
	
}

$content = ob_get_clean();
return $content;
	
}
else {
ob_start();	
echo '<div style="display: none;"></div>';	
$contnt = ob_get_clean();
return $contnt;
	}

}

add_shortcode( 'easymedia-gallery', 'easy_media_gnl_shortcode' );


function easy_media_category( $attsn ) {

if ( easy_get_option( 'easymedia_disen_plug' ) == '1' ) {
	
	extract( shortcode_atts( array(
	'cat' => -1,
	'style' => '',
	'filter' => '',	
	'mark' => '',
	'pag' => '',
	'def' => '',		
	'size' => ''
	), $attsn ) );
	
	ob_start();
	
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // for pagination
	
$deff_img_limit = easy_get_option( 'easymedia_img_size_limit' ); // get the default image size limit
$theopt = easy_get_option( 'easymedia_frm_size' ); 	 
$showbadge = easy_get_option( 'easymedia_disen_showcntrthumb' );  

// Custom Filter	
if ( $def != '' ) {
	echo '<script>var fodr = []; fodr[0] = ".fltr'.$def.'"; </script>';
	$clssltdall = 'class=""';
	} else {
		$def = '*';
		echo '<script>var fodr = []; fodr[0] = "'.$def.'"; </script>';
		$clssltdall = 'class="selected"';
		}
		
// Custom Style		
if ( $style != '' ) {
	if ( easy_get_option( 'easymedia_disen_style_man' ) == '1' ) {
	$cus_style = ucfirst( $style );
		} else { $cus_style = easy_get_option( 'easymedia_box_style' ); }
	} else {
		$cus_style = easy_get_option( 'easymedia_box_style' );
	}
	
// Custom size filter	
	if ( $size != '' ) {
		$sizeval = explode(",", $size);
			if ( $sizeval[0] > 0 && $sizeval[1] > 0 && is_numeric( $sizeval[0] ) && is_numeric( $sizeval[1] ) ) { 
				$imwidth = $sizeval[0];
				$imheight = $sizeval[1];
			} else {
				$imwidth = stripslashes( $theopt['width'] );
				$imheight = stripslashes( $theopt['height'] );
				}	
			}
	else {
		$imwidth = stripslashes( $theopt['width'] );
		$imheight = stripslashes( $theopt['height'] );
	}   

if ( $cat > '0' ) {
	
	$finid = explode(",", $cat);
	$medinarr = $finid;	
	
$emgargs = array( 
    'post_type' => 'easymediagallery',
    'showposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
    'order' => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'emediagallery',
            'terms' => $finid,
            'field' => 'term_id',
        )
    ),
);	
}
 

 
$emg_query = new WP_Query( $emgargs );
if ( $emg_query->have_posts() ):
$mediauniqueid = RandomString(6); //Random class for fitText

	if ( $filter != '' && $pag == '' ) {
        echo'<section id="emgoptions" class="emgclearfix"><ul id="filters" class="portfolio-tabs emgoption-set emgclearfix" data-option-key="filter">';
        echo'<li><a href="#filter" data-option-value="*" '.$clssltdall.' id="emgshowall">' . __( 'Show All', 'easmedia' ) . '</a></li>';
		
		foreach( $medinarr as $eachcat ) {
		$terms = get_term( $eachcat, 'emediagallery' );
		$filtid = $terms->name; 
		
			if ( $def == $eachcat) {
				$clssdeffil = 'class="selected"';
				} else { $clssdeffil = 'class=""'; }		
		
		if ( $filtid ) {
		echo'<li><a href="#filter" '.$clssdeffil.' data-option-value=".fltr'.$eachcat.'">'.$filtid.'</a></li>';	
		} else {echo'<li><a href="#filter" data-option-value=".fltruncategorized">Uncategorized</a></li>';}

		}
	
      echo'</ul></section>';
}

if ( $pag != '' ) { 
echo '<div class="pagwrap" id="'.$pag .'"><div id="pag-legend2" style="display:none;"></div><div class="emgpagntn easymedia_center emgclearfix">';
} else {
echo '<div class="emgajxloader"></div><div style="display: none;" class="pagwrap" id="nopagination"><div class="easycontainer easymedia_center emgclearfix">';	
}

	while ( $emg_query->have_posts() ) : $emg_query->the_post();

		//$image = wp_get_attachment_url( get_post_thumbnail_id( get_the_id() ) );
		//$image = get_the_post_thumbnail( get_the_id(), 'work-admin-thumb' ) ;
		$image = get_post_meta( get_the_id(), 'easmedia_metabox_img', true );
		$mediattl = esc_html( esc_js( get_post_meta( get_the_id(), 'easmedia_metabox_title', true ) ) ); $mediattl = stripslashes($mediattl);
		$mediatype = get_post_meta( get_the_id(), 'easmedia_metabox_media_type', true );
		$isvidsize = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size', true );
		$ismapsize = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap_size', true );
		$galleryid = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_id', true );	
		$isresize = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_opt1', true );
		$isresize1 = get_post_meta( get_the_id(), 'easmedia_metabox_media_image_opt1', true );
		$usegalleryinfo = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery_opt2', true );
		$link_type = get_post_meta( get_the_id(), 'easmedia_metabox_media_link_opt1', true );
		$thepostid = get_the_id();
		$medcat = wp_get_post_terms( get_the_id(), 'emediagallery' );
		
		
/*  Version 1.3.1.3 - 1.3.1.5
		if ($medcat) {
		foreach ( $medcat as $cat ) {
			$mediaid= $cat->term_id;
			unset($medcat);
		} } else {$mediaid = 'uncategorized'; unset($medcat);}			
*/	
		
/*@since 1.3.1.7		*/	
		$terms = get_the_terms( get_the_id(), 'emediagallery' );
		if ( $terms && ! is_wp_error( $terms ) ) :
		$mcatid = array();
		foreach ( $terms as $term ) {
			$mcatid[] = $term->term_id;
			}
			$mediaid = "fltr". join( " fltr", $mcatid );
			endif;
					
			if ( $image == '' ) {
				$image = plugins_url( 'images/no-image-available.jpg' , __FILE__ ) ;
				}
				else {
					$image = $image;
					}

		switch ( $mediatype ) {
			case 'Single Image':
				
				if ( basename( $image ) == 'no-image-available.jpg' ) {
					$medialink = $image;
				}
					else {
				$attid = wp_get_attachment_image_src( emg_get_attachment_id_from_src( $image ), 'full' );
				$medialink = easymedia_imgresize( $attid[0], $deff_img_limit, $isresize1, $attid[1], $attid[2] );
				$medialink = explode(",", $medialink); $medialink = $medialink[0];
					}
					if ( $mark ) {
				$therell = "easymedia[" .$mark."]";
				} else {
					$therell = "easymedia";
					}
				
				
	    	break;
			
			case 'Multiple Images (Slider)':
							
				if ( $pag != '' ) {
					$therell = "easymedia[".$mediauniqueid."]";
					} else {
						$therell = "easymedia[".$galleryid."]";
						}
		
				$images = get_post_meta( get_the_id(), 'easmedia_metabox_media_gallery', true );
				
			ob_start();
				if ( is_array( $images ) ) {
					$ig = 0;
					echo '<div id="easymedia_gallerycontainer-'.$mediauniqueid.'" style="display:none">';
					foreach( $images as $img_id ) {
						
							//Changelog version 1.3.1.3 => Set 1st Image Gallery
							if($ig++ == 0) {
								$img = wp_get_attachment_image_src($img_id, 'full');
								$frstimg = $img_id;
								$medialink = easymedia_imgresize( $img[0], $deff_img_limit, $isresize, $img[1], $img[2] );
								$medialink = explode(",", $medialink); $medialink = $medialink[0];
								}
																
						$img = wp_get_attachment_image_src($img_id, 'full');
						$img_url = easymedia_imgresize( $img[0], $deff_img_limit, $isresize, $img[1], $img[2] );
                        $img_url = explode(",", $img_url); ?>
                	<a class="<?php echo $thepostid; ?>-<?php echo $img_id; ?>" href="<?php echo $img_url[0]; ?>" rel="<?php echo $therell; ?>"></a>
            		<?php
					$imgcount = $ig;
				} echo '</div>'; }
				else {
				echo '<div style="display:none"></div>';
				}
		$galle = ob_get_clean();
		if ($imgcount <= 1) {$sorn =  __( 'image', 'easmedia' );} else {$sorn = __( 'images', 'easmedia' );}

			break;			
			
			case 'Video':
				$vidcover = get_post_meta( get_the_id(), 'easmedia_metabox_img', true );
				$vidlink1 = get_post_meta( get_the_id(), 'easmedia_metabox_media_video', true );
				$vidlink2 = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_webm', true );
				$vidlink3 = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_ogg', true );

				if ( $vidlink1 != '' ) { $vidlink1 = $vidlink1;} else {$vidlink1 = '-';}
				if ( $vidlink2 != '' ) { $vidlink2 = $vidlink2;} else {$vidlink2 = '-';}
				if ( $vidlink3 != '' ) { $vidlink3 = $vidlink3;} else {$vidlink3 = '-';}
				if ( $vidcover != '' ) { $vidcover = $vidcover;} else {$vidcover = '-';}
				
				
	
				if ( pathinfo($vidlink1, PATHINFO_EXTENSION) == 'mp4' || pathinfo($vidlink2, PATHINFO_EXTENSION) == 'webm' || pathinfo($vidlink3, PATHINFO_EXTENSION) == 'ogv' || pathinfo($vidlink1, PATHINFO_EXTENSION) == 'wmv') {
					$medialink = $vidlink1.'#emg#'.$vidlink2.'#emg#'.$vidlink3.'#emg#'.emg_replace_extension($vidcover); }
					else {
						$medialink = $vidlink1; }
				
		if ( $mediatype == 'Video' && $isvidsize == 'off' ) {
			$cusvidw = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size_vidw', true );
			$cusvidh = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size_vidh', true );
			$therell = "easymedia[".$cusvidw." " .$cusvidh."]";
			}
		elseif ( $mediatype == 'Video' && $isvidsize == 'on' ) {
			$getarry = easy_get_option( 'easymedia_vid_size' );
			$defvidw = stripslashes( $getarry['width'] );
			$defvidh = stripslashes( $getarry['height'] );
			$therell = "easymedia[".$defvidw." " .$defvidh."]";
			}
		else {
			$therell = "easymedia";
			}				

	        break;
			
			case 'Google Maps':
				$medialink = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap', true ) ."&amp;output=embed";
				
		if ( $mediatype == 'Google Maps' && $ismapsize == 'off' ) {
			$cusgmw = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap_size_gmidw', true );
			$cusgmh = get_post_meta( get_the_id(), 'easmedia_metabox_media_gmap_size_gmidh', true );
			$therell = "easymedia[".$cusgmw." " .$cusgmh."]";
			}
		elseif ( $mediatype == 'Google Maps' && $ismapsize == 'on' ) {
			$getarry = easy_get_option( 'easymedia_gmap_size' );
			$defgmw = stripslashes( $getarry['width'] );
			$defgmh = stripslashes( $getarry['height'] );
			$therell = "easymedia[".$defgmw." " .$defgmh."]";
			}
		else {
			$therell = "easymedia";
			}					
				
	        break;			
			
			case 'Audio':
			$curaudiosource = get_post_meta(get_the_id(), 'easmedia_metabox_media_audio_source', true);
			$medialinktmp = get_post_meta( get_the_id(), 'easmedia_metabox_media_audio', true );
			$medialink = get_post_meta( get_the_id(), 'easmedia_metabox_media_audio', true );
							
				
					if ( $mark ) {
				$therell = "easymedia[" .$mark."]";
				} else {
					$therell = "easymedia";
					}
					
					if ( $curaudiosource == 'soundcloud.com' ) {
				$therell = "easymedia[600 170]";
				} else {

					$therell = "easymedia";
					}					
					
				
	        break;			
			
			case 'Link':
				$media_link = get_post_meta( get_the_id(), 'easmedia_metabox_media_link', true );
				if ( $media_link !='' ) {
					if ( substr( $media_link, 0, 4 ) === 'http' || substr( $media_link, 0, 5 ) === 'https' ) {
						$media_link_fin = $media_link; 
						}
						else {
							$media_link_fin = 'http://' .$media_link; 
						}
					}
					else {
					$media_link_fin = $post->guid;
					}
					
				$medialink = $media_link_fin;
				$therell = "";
	        break;		
		}
		
			$emgthumbimg = emg_thumb_src( $image, $imwidth, $imheight, '0', '0' );		
		
	  	  	$curimgnmane = basename($image);
			if ( $curimgnmane == 'no-image-available.jpg' ) {
				$emgthumbimg = $image;
				} else {
					$emgthumbimg = $emgthumbimg;
					}
					
				if ( $pag != '' ) {
					$theclass = 'peasyitem';
					} else {
						$theclass = 'easyitem';
						}				
						
		if ( $mediatype == 'Video' && get_post_meta(get_the_id(), 'easmedia_metabox_media_video_fetchurl', true) != '' ) {
			$emgthumbimg = get_post_meta(get_the_id(), 'easmedia_metabox_media_video_fetchurl', true);			
		}
		
		if ( $showbadge == '1' && $mediatype == 'Multiple Images (Slider)' ){
			$addbadge = '<span class="emg-badges"><span class="icount">'.$imgcount.'</span><span class="imgtg">'.$sorn.'</span></span>';
		} else {$addbadge = '';}										
					
	  if ( easy_get_option( 'easymedia_disen_hovstyle' ) == '1' ) { ?>
     <div style="width:<?php echo $imwidth; ?>px; height:<?php echo $imheight; ?>px;" class="<?php echo $theclass; ?> view da-thumbs preloaderview <?php echo $mediaid; ?>"><?php echo $addbadge; ?><div class="iehand"><img data-original="<?php echo $emgthumbimg; ?>" /><a onclick="easyActiveStyleSheet('<?php echo $cus_style; ?>');return true;" class="<?php if ( $mediatype == 'Multiple Images (Slider)' && $usegalleryinfo == 'on' ) { echo $thepostid.'-'.$frstimg; } else { echo $thepostid; } ?>" rel="<?php echo $therell; ?>" href="<?php echo $medialink; ?>" <?php if ( $link_type == 'on' && $mediatype == 'Link' ) { echo 'target="_blank"'; } ?>><article class="da-animate da-slideFromRight"><p <?php if ( $mediattl == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext"><?php echo $mediattl; ?></p><div class="forspan"><span class="zoom"></span></div></article></a></div></div>
            
<?php } elseif ( easy_get_option( 'easymedia_disen_hovstyle' ) == '' ) { ?>
<div class="<?php echo $theclass; ?> view da-thumbs preloaderview <?php echo $mediaid; ?>" style="width:<?php echo $imwidth; ?>px; height:<?php echo $imheight; ?>px;"><?php echo $addbadge; ?><div class="iehand"><a onclick="easyActiveStyleSheet('<?php echo $cus_style; ?>');return true;" class="<?php if ( $mediatype == 'Multiple Images (Slider)' && $usegalleryinfo == 'on' ) { echo $thepostid.'-'.$frstimg; } else { echo $thepostid; } ?>" rel="<?php echo $therell; ?>" href="<?php echo $medialink; ?>" <?php if ( $link_type == 'on' && $mediatype == 'Link' ) { echo 'target="_blank"'; } ?>><img data-original="<?php echo $emgthumbimg; ?>"/><p <?php if ( $mediattl == '' ) { echo 'style="display:none !important;"'; } ?> class="da-animatenh emgfittext" style="display:none;"><?php echo $mediattl; ?></p><div class="forspana"><span class="zooma"></span></div></a></div></div>
<?php	}


		//Changelog version 1.0.1.0 => Generate Image Gallery
		if ( $mediatype == 'Multiple Images (Slider)' ) {
			echo $galle;
		}




    endwhile;

else:
echo '<div class="easymedia_center">'; 
echo '<div class="view"><img src="'.plugins_url('images/ajax-loader.gif' , __FILE__).'" width="32" height="32"/></div>';	
$contnt = ob_get_clean();
return $contnt;  

endif;
wp_reset_postdata();
echo '<div style="clear:both;"></div>';

if ( $pag != '' ) { 
echo '</div><div class="emg-pag-holder"></div></div>';
} else {
echo '</div></div>';	
}

$content = ob_get_clean();
return $content;
	
}
else {
ob_start();	
echo '<div style="display: none;"></div>';	
$contnt = ob_get_clean();
return $contnt;
	}

}

add_shortcode( 'easy-mediagallery', 'easy_media_category' );



?>