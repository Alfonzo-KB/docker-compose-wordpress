<?php
// add_action('wp_loaded', 'lazyLoadVideos');
function lazyLoadVideos(){
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        //  Check to see if youtube wrapper exists
        if($(".youtube-video-place")[0]){
            var video_wrapper = $('.youtube-video-place');
            // If user clicks on the video wrapper load the video.
            $('.play-youtube-video').on('click', function(){
                video_wrapper.html('<iframe allowfullscreen frameborder="0" width=1140" height="570" class="embed-responsive-item" src="' + video_wrapper.data('yt-url') + '&autoplay=1"></iframe>');
            });
        }
    });
    </script>
    <style>
        .youtube-video-place{
            cursor: pointer;
        } 
    </style>
    <?php
}
?>
