;(function($) {

    // Scroll to Top
    // $(window).scroll(function(){
    //     if($(this).scrollTop()>100){
    //         $("#up").fadeIn();
    //     }else{
    //         $("#up").fadeOut();
    //     }
    // });

    $("#up").click(function(){
        $("html, body").animate({scrollTop:0},1000)
    });

$('.news-ticker-h').show();
 
    jQuery(document).ready(function($) {
        // Copy number/link function
        function copyText(text) {
            var temp = $('<input>');
            $('body').append(temp);
            temp.val(text).select();
            document.execCommand('copy');
            temp.remove();
        }
    
        // Copy button click event
        $('.copy-btn').click(function() {
            var $value2 = $(this).siblings('.value-2');
            var valueToCopy = $value2.text().trim();
            console.log('Value to copy:', valueToCopy); // Log the value to be copied
            console.log('Value-2 element:', $value2); // Log the value-2 element for further inspection
            copyText(valueToCopy);
            // Optionally, you can provide feedback to the user that the text has been copied.
            alert('Copied: ' + valueToCopy);
        });
    
        // Toggle social-connection-group
        $('.focus-part').click(function() {
            $(this).next('.social-connection-group').slideToggle();
            $(this).toggleClass("active");
        });
    });


    $(document).ready(function(){ 
        // Change the placeholder attribute of the input field
        $('.search-field').attr('placeholder', 'সার্চ...');
    });


    $(document).ready(function() {
        $(window).scroll(function() {
          var scrollPos = $(window).scrollTop();
          var mastheadHeight = $('#masthead').height();
          var arrowImage = $('.top-arrow-image');
      
          // If masthead is out of window, add active class to arrow image
          if (scrollPos > mastheadHeight) {
            arrowImage.addClass('active');
          } else {
            arrowImage.removeClass('active');
          }
        });
      });
	
	jQuery(document).ready(function($) {
    $('.click1').on('click', function() {
        $('.click1').addClass('active');
        $('.click2').removeClass('active');

        $('.body-item1').show().addClass('active');
        $('.body-item2').hide().removeClass('active');
    });

    $('.click2').on('click', function() {
        $('.click2').addClass('active');
        $('.click1').removeClass('active');

        $('.body-item2').show().addClass('active');
        $('.body-item1').hide().removeClass('active');
    });
});
	
	
})(jQuery);




