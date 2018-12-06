$(window).on('load', function() {

        /* Slide in the feedback message - we suggest doing this only once per session */
        setTimeout(function(){
            $('.usrp-fb-2').addClass('slide-in');
        }, 1000);

        /* Bind actions to small buttons click */
        $('.usrp-fb-2 .usrp-fb-btn').on('click', function(){

            /* Open feedback forum if "Yes" button was clicked in feedback message */
            if ($(this).hasClass('usrp-fb-btn-yes')) {
                // setTimeout(function(){ _urq.push(['Feedback_Open']); }, 300);
                $('.textFeedback').fadeOut('slow', function(){
                  $('.formFeedback').fadeIn('slow');
                });
            } else {
              /* Collapse the feedback message into a regular button */
              $('.usrp-fb-2').removeClass('is-expanded')
              setTimeout(function(){ $('.usrp-fb-2').addClass('is-collapsed'); }, 300)
            }

        });

        /* Open feedback forum on collapsed button click */
        $('.usrp-fb-2').click(function(){
            if ($(this).hasClass('is-collapsed')) {
                $('.usrp-fb-2').removeClass('is-collapsed').addClass('is-expanded');
            };
        });

	});
