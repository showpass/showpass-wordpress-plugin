<?php 
  namespace Showpass;
  use Exception;

  class ImageFormatter {

    const CLOUDFRONT_REGEX = '/(https:\/\/\w*\.cloudfront.net\/)/';
    const BREAKPOINTS = [[960, '(max-width: 960px) and (min-width: 781px)'], [780, '(max-width: 780px) and (min-width: 601px)'], [600, '(max-width: 600px) and (min-width: 376px)'], [375, '(max-width: 375px)']];
    const IMAGE_FORMAT = 'jpeg';
    private $cloudfront_base_url = 'https://dcm1eeuyachdi.cloudfront.net/';

    function __construct() {
      if (get_option('option_use_showpass_beta')) {
        $this->cloudfront_base_url = 'https://db9zval7bk53o.cloudfront.net/';
      } else if (get_option('option_use_showpass_demo')) {
        $this->cloudfront_base_url = 'https://d2sv1t07lr5mwo.cloudfront.net/';
      }
    }

      /**
       * Creates a responsive image using the CloudFront service.
       * Will create a <picture> <source> for each breakpoint/size.
       * 
       * @param String $src - img src
       * @param Array $options - options key/value array
       */
    public function getResponsiveImage(
      $src, 
      $options = []
    ) {
      /**
       * Get all options and set defaults.
       */
      $attr = isset($options['attr']) ? $options['attr'] : [];
      $format = isset($options['image-format']) ? $options['image-format'] : self::IMAGE_FORMAT;
      $breakpoints = isset($options['breakpoints']) ? $options['breakpoints'] : self::BREAKPOINTS;

      // add alt to attributes so it can be printed out on the img tag
      if (isset($options['alt'])) {
        $attr['alt'] = $options['alt'];
      }
      // add title to attributes so it can be printed out on the img tag
      if (isset($options['title'])) {
        $attr['title'] = $options['title'];
      }

      /**
       * Get relative path to image.
       * 
       * $splitURL[0] = base
       * $splitURL[1] = relative path
       */
      $splitURL = preg_split(self::CLOUDFRONT_REGEX, $src, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

      if (count($splitURL) < 2) {
        // create attributes string
        $attributes = '';
        foreach ($attr as $key => $value) {
          $attributes .= sprintf('%s="%s" ', $key, $value);
        }
        unset($key, $value);

        return sprintf('<img src="%s" %s />', $src, $attributes);
      } 

      $sources = [];
      for ($i = 0; $i < count($breakpoints); $i++) {
        // create source element
        $imgSize = sprintf('fit-in/%sx%s/', $breakpoints[$i][0], $breakpoints[$i][0]);
        $imgFormat = sprintf('filters:format(%s)/', $format);
        $imgSrc = $this->cloudfront_base_url . $imgSize .  $imgFormat . $splitURL[1];

        // add source to sources array
        $sources[$i] = sprintf('<source media="%s" srcset="%s">', $breakpoints[$i][1], $imgSrc);
      }
      unset($i, $imgSize, $imgFormat, $imgSrc);

      return $this->generateResponsiveTag($src, $sources, $attr);
    }

    /**
     * Creates an html <picture> tag.
     * Generates a <source> tag for each item in the $source array
     * 
     * @param String $default - default img src
     * @param Array $sources - source image array
     * @param Array $attr - html attributes to apply to the <picture> tag.
     */
    private function generateResponsiveTag($default, $sources, $attr = []) {
      // create attributes string
      $attributes = '';
      foreach ($attr as $key => $value) {
        $attributes .= sprintf('%s="%s" ', $key, $value);
      }
      unset($key, $value);

      // create picture element
      $picture = '<picture>';
      // add all sources to picture
      foreach ($sources as &$source) {
        $picture .= $source;
      }
      unset($source);

      // set default image
      $picture .= sprintf('<img src="%s" %s>', $default, $attributes);
      $picture .= '</picture>';

      return $picture;
    }
  }
?>