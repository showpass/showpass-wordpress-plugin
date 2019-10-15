<?php 
  namespace Showpass;

  class ImageFormater {

    const CLOUDFRONT_REGEX = '/(https:\/\/\w*\.cloudfront.net\/)/';
    const CLOUDFRON_BASE_URL = 'https://dcm1eeuyachdi.cloudfront.net/';
    const BREAKPOINTS = [[960, '(max-width: 960px) and (min-width: 781px)'], [780, '(max-width: 780px) and (min-width: 601px)'], [600, '(max-width: 600px) and (min-width: 376px)'], [375, '(max-width: 375px)']];
    const IMAGE_FORMAT = 'jpeg';

    function __construct() {}

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

      $sources = [];
      for ($i = 0; $i < count($breakpoints); $i++) {
        // create source element
        $imgSize = sprintf('fit-in/%sx%s/', $breakpoints[$i][0], $breakpoints[$i][0]);
        $imgFormat = sprintf('filters:format(%s)/', $format);
        $imgSrc = self::CLOUDFRON_BASE_URL . $imgSize .  $imgFormat . $splitURL[1];

        // add source to sources array
        $sources[$i] = sprintf('<source media="%s" srcset="%s">', $breakpoints[$i][1], $imgSrc);
      }
      unset($i, $imgSize, $imgFormat, $imgSrc);

      return $this->generateResponsiveTag($src, $sources, $attr);
    }

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