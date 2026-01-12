<?php
get_header();

global $post; // Access the global $post variable

// Check if it's a regular blog post and use the blog details template
if (get_post_type($post) == 'post') {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/post/content', 'single' );
    }
} elseif (get_post_type($post) == 'ninequiz') {

// Get meta field values
// $quiz_date = get_post_meta(get_the_ID(), 'quiz_date', true);
// $quiz_time_hour = get_post_meta(get_the_ID(), 'quiz_count_time_hour', true);
// $quiz_time_minute = get_post_meta(get_the_ID(), 'quiz_count_time_minute', true);
// $quiz_time_second = get_post_meta(get_the_ID(), 'quiz_count_time_second', true);
// $quiz_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); 
// // Get the post thumbnail
// $quiz_link = get_permalink(); // Post link
// $quiz_title = get_the_title();
// $quiz_form_id = get_post_meta(get_the_ID(), 'quiz_form_id', true); // Get quiz_form_id meta field
// $timezone = 'Asia/Dhaka'; // Hardcoded timezone "Dhaka"



$first_prize_title = get_post_meta(get_the_ID(), 'first_prize_title', true);
$second_prize_title = get_post_meta(get_the_ID(), 'second_prize_title', true);
$third_prize_title = get_post_meta(get_the_ID(), 'third_prize_title', true);
$four_prize_title = get_post_meta(get_the_ID(), 'four_prize_title', true);



// // Ensure numeric values for time fields
// $quiz_time_hour = isset($quiz_time_hour) && is_numeric($quiz_time_hour) ? $quiz_time_hour : 0;
// $quiz_time_minute = isset($quiz_time_minute) && is_numeric($quiz_time_minute) ? $quiz_time_minute : 0;
// $quiz_time_second = isset($quiz_time_second) && is_numeric($quiz_time_second) ? $quiz_time_second : 0;

// $total_seconds = ($quiz_time_hour * 3600) + ($quiz_time_minute * 60) + $quiz_time_second;

// $target_time = !empty($quiz_date) ? date('Y-m-d H:i:s', strtotime($quiz_date)) : '';



// $first_prize_title = get_post_meta(get_the_ID(), 'first_prize_title', true);
// $second_prize_title = get_post_meta(get_the_ID(), 'second_prize_title', true);
// $third_prize_title = get_post_meta(get_the_ID(), 'third_prize_title', true);
// $four_prize_title = get_post_meta(get_the_ID(), 'four_prize_title', true);


// Get meta field values
$quiz_date = get_post_meta(get_the_ID(), 'quiz_date', true); // Start date/time
$quiz_end_date_time = get_post_meta(get_the_ID(), 'end_time', true); // End date/time
$quiz_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); // Get the post thumbnail URL
$quiz_link = get_permalink(); // Post link
$quiz_title = get_the_title();
$quiz_form_id = get_post_meta(get_the_ID(), 'quiz_form_id', true); // Get quiz_form_id meta field
$timezone = 'Asia/Dhaka'; // Hardcoded timezone "Dhaka"

// Prize Titles
$first_prize_title = get_post_meta(get_the_ID(), 'first_prize_title', true);
$second_prize_title = get_post_meta(get_the_ID(), 'second_prize_title', true);
$third_prize_title = get_post_meta(get_the_ID(), 'third_prize_title', true);
$four_prize_title = get_post_meta(get_the_ID(), 'four_prize_title', true);

// Ensure valid datetime for quiz start and end time
$quiz_date = !empty($quiz_date) ? date('Y-m-d H:i:s', strtotime($quiz_date)) : '';
$quiz_end_date_time = !empty($quiz_end_date_time) ? date('Y-m-d H:i:s', strtotime($quiz_end_date_time)) : '';

// If both quiz date and quiz end time are valid, calculate the duration
if ($quiz_date && $quiz_end_date_time) {
    $start_time = new DateTime($quiz_date, new DateTimeZone($timezone));
    $end_time = new DateTime($quiz_end_date_time, new DateTimeZone($timezone));
    
    // Calculate the difference between start and end time
    $interval = $start_time->diff($end_time);

    // Calculate total duration in seconds
    $total_seconds = ($interval->y * 365 * 24 * 60 * 60) + // Years to seconds
                     ($interval->m * 30 * 24 * 60 * 60) + // Months to seconds (approximated to 30 days)
                     ($interval->d * 24 * 60 * 60) + // Days to seconds
                     ($interval->h * 3600) + // Hours to seconds
                     ($interval->i * 60) + // Minutes to seconds
                     $interval->s; // Seconds

} else {
    // If any of the times are invalid, set total_seconds to 0
    $total_seconds = 0;
}

// Prepare target time based on quiz start date/time
$target_time = $quiz_date ? date('Y-m-d H:i:s', strtotime($quiz_date)) : '';


?>



<div class="left-part">

    <div class="quz-header">
        <div class="img-header">
            <img class="thumb" src="<?= esc_url($quiz_image_url) ?>" alt="<?= esc_attr($quiz_title) ?>">
        </div>

		<div class="title-time">
			
		
        <div class="quiz-title">
            <?php echo $quiz_title; ?> 
        </div>
        <div class="timer-box">
            <div id="current-time" style="display: none;">--:--:--</div>
            <div id="countdown-container">
                <div id="ar-baki" class="ar-baki">সময় বাকি</div>
                <div id="countdown" data-value="<?= $total_seconds ?>">00:00</div>
                <div id="countdown-coming">
					
					<div id="ar-baki-come" class="ar-baki">সময় বাকি:</div>
					
					<div>
						Coming Soon...
					</div>
				</div>
                <div id="countdown-closed">
					<div class="close-sad-pp">
						<div class="close-sad-tt">
							<div id="ar-baki-close" class="ar-baki">সময় বাকি</div>
                		  <div id="countdown-close" data-value="<?= $total_seconds ?>">00:00:00</div>
						</div>

						
					</div>
				</div>

                <div id="targetTime" data-value="<?= $target_time ?>" style="display: none;"></div>
                <div id="timezone" data-value="<?= $timezone ?>" style="display: none;"></div>
                <div id="quizFormId" data-value="<?= $quiz_form_id ?>" style="display: none;"></div>
                <!-- Passing quiz_form_id -->
            </div>
        </div>
		</div>

    </div>

    <ul class="custom-accordion">
		
		
<!-- 		   <li class="join-quiz custom-accordion-item">
            <h3 class="custom-accordion-thumb">কুইজের অংশগ্রহণের নিয়ম</h3>
            <div class="custom-accordion-panel">

				<?php
				//$gift_meta = get_post_meta( get_the_ID(), 'quiz_rule', true ); // Retrieve the 'gift-1' meta field value

				if ( ! empty( $gift_meta ) ) {
					//echo ( $gift_meta ); // Display the 'gift-1' meta field value if it exists
				}
				?>

            </div>
        </li> -->
		
		
		
        <li class="form-part custom-accordion-item is-active">
            <h3 class="custom-accordion-thumb">কুইজে অংশগ্রহণ করুন</h3>
            <div class="custom-accordion-panel">
				
				
				<div class="inner-role">
					<div class="custom-accordion-thumb">কুইজের অংশগ্রহণের নিয়ম</div>
					<div class="custom-qz-rule">

						<?php
						$gift_meta = get_post_meta( get_the_ID(), 'quiz_rule', true ); // Retrieve the 'gift-1' meta field value
	

						if ( ! empty( $gift_meta ) ) {
							echo ( $gift_meta ); // Display the 'gift-1' meta field value if it exists
						}
						?>

					</div>				
				</div>
				
				
                <div class="form-section">
                    <div id="quiz-end" class="quiz-end-part" style="dispaly:none">
						
							<div class="close-sad">
							  <img src="https://agent9w.com/wp-content/uploads/2024/10/Vector.png" alt="close">
							</div>
						
						
						<div class="sad-grp">
							<div class="sad-grp-1">
								দুঃখিত!!
							</div>
							<div class="sad-grp-2">
								দুঃখিত কুইজে অংশগ্রহণের সময়সীমা শেষ
							</div>
						</div>

						
					</div>
                    <div id="quiz-coming" style="dispaly:none">
						
						        <div class="img-come">
									<img class="com" src="https://agent9w.com/wp-content/uploads/2024/10/Vector-2.png" alt="">
								</div>
						
						<div>
							Coming Soon...
						</div>
						<div>
							কুইজ অতি শীঘ্রই আরম্ভ হতে যাচ্ছে। আমাদের সাথে থাকার জন্য ধন্যবাদ।
						</div>
					</div>
					
                    <div id="quiz-form-container" style="display:none">
						<?php echo FrmFormsController::get_form_shortcode( array( 'id' => $quiz_form_id ) ); ?>
					</div>
                    <!-- Placeholder for the Formidable form shortcode -->
                </div>
            </div>
        </li>



        <li class="quiz-result custom-accordion-item">
            <h3 class="custom-accordion-thumb">কুইজ রেজাল্ট</h3>
            <div class="custom-accordion-panel">
				
				
				
				<?php
// Fetch the results for each group
$group_1_results = get_post_meta(get_the_ID(), 'ninequiz_result_group_1', true);
$group_2_results = get_post_meta(get_the_ID(), 'ninequiz_result_group_2', true);
$group_3_results = get_post_meta(get_the_ID(), 'ninequiz_result_group_3', true);
$group_4_results = get_post_meta(get_the_ID(), 'ninequiz_result_group_4', true);

// Function to display results
function display_results($group_title, $results) {
    // Check if results are not empty and are an array
    if (!empty($results) && is_array($results)) {
        $has_valid_entries = false; // Flag to check if there are any valid entries

        // Iterate over the results to find valid entries
        foreach ($results as $result) {
            // Validate each result's fields
            if (!empty($result['name']) || !empty($result['email']) || !empty($result['phone']) || !empty($result['description'])) {
                $has_valid_entries = true; // Set the flag if any field is valid
                break; // No need to continue checking
            }
        }

        // Only display the group title and results if there are valid entries
        if ($has_valid_entries) {
            echo '<h2>' . esc_html($group_title) . '</h2>';
            echo '<ol>';
            foreach ($results as $result) {
                // Validate each result's fields before displaying
                if (!empty($result['name']) || !empty($result['email']) || !empty($result['phone']) || !empty($result['description'])) {
                    echo '<li>';
                    echo '<span class="result-name">' . esc_html($result['name']) . '</span>';
                    
                    // Separate spans for email and phone
                    echo '<span class="em-ph"><span class="result-email">' . esc_html($result['email']) . '</span>';
                    echo '<span class="result-phone">' . esc_html($result['phone']) . '</span></span>';
                    
                    echo '<span class="result-description">' . esc_html($result['description']) . '</span>';
                    echo '</li>';
                }
            }
            echo '</ol>';
        }
    }
}

	
	
// Check array sizes
$group_1_size = count($group_1_results);
$group_2_size = count($group_2_results);
$group_3_size = count($group_3_results);
$group_4_size = count($group_4_results);
				 
					 
// Validate and display Group 1 Results
// echo $group_1_size;
// echo $group_2_size;
// echo $group_3_size;
// echo $group_4_size;
	
function hasValues($group) {
    foreach ($group as $entry) {
        foreach ($entry as $key => $value) {
            if (!empty($value)) {
                return true;
            }
        }
    }
    return false;
}

// Check Group 1 Results
if (!empty($group_1_results) && hasValues($group_1_results)) {
	echo "<div class='group-1-res group-res'>";
    echo "<div class='group-1'>১</div>";
    display_results($first_prize_title, $group_1_results);
	echo "</div>";
}

// Check Group 2 Results
if (!empty($group_2_results) && hasValues($group_2_results)) {
	echo "<div class='group-2-res group-res'>";
    echo "<div class='group-2'>২</div>";
    display_results($second_prize_title, $group_2_results);
	echo "</div>";
}

// Check Group 3 Results
if (!empty($group_3_results) && hasValues($group_3_results)) {
	echo "<div class='group-3-res group-res'>";
    echo "<div class='group-3'>৩</div>";
    display_results($third_prize_title, $group_3_results);
	echo "</div>";
}

// Check Group 4 Results
if (!empty($group_4_results) && hasValues($group_4_results)) {
	echo "<div class='group-4-res group-res'>";
    echo "<div class='group-4'>৪</div>";
    display_results($four_prize_title, $group_4_results);
	echo "</div>";
}
?>



				<?php
// 				var_dump($group_4_results);
// 				var_dump($group_3_results);
// 				var_dump($group_2_results);
// 				var_dump($group_1_results);
	
	
	
	// Check if all values in all groups are empty
$allGroupsEmpty = true;  // Assume all groups are empty until proven otherwise

// Check group 1
if (!empty($group_1_results[0]['name']) || !empty($group_1_results[0]['email']) || !empty($group_1_results[0]['phone']) || !empty($group_1_results[0]['description'])) {
    $allGroupsEmpty = false;
}

// Check group 2
if (!empty($group_2_results[0]['name']) || !empty($group_2_results[0]['email']) || !empty($group_2_results[0]['phone']) || !empty($group_2_results[0]['description'])) {
    $allGroupsEmpty = false;
}

// Check group 3
if (!empty($group_3_results[0]['name']) || !empty($group_3_results[0]['email']) || !empty($group_3_results[0]['phone']) || !empty($group_3_results[0]['description'])) {
    $allGroupsEmpty = false;
}

// Check group 4
if (!empty($group_4_results[0]['name']) || !empty($group_4_results[0]['email']) || !empty($group_4_results[0]['phone']) || !empty($group_4_results[0]['description'])) {
    $allGroupsEmpty = false;
}

// Output result
if ($allGroupsEmpty) {
    //echo "All groups have empty values.";
} else {
   // echo "At least one group has non-empty values.";
}
	


	//if ($group_1_size == 1 && $group_2_size == 1 && $group_3_size == 1 && $group_4_size == 1) {
	
	if ($allGroupsEmpty) {


	
//		if ($group_1_size == 1) {
//     echo "All groups are empty.";
	 ?>
				<div class="no-result-found">
					
							<div class="result-sad">
							  <img src="https://agent9w.com/wp-content/uploads/2024/10/Vector-1.png" alt="close">
							</div>
					
					
					
					<div class="no-re-1">
						কোন রেজাল্ট পাওয়া যায়নি
					</div>
					<div class="no-re-2">
						কুইজ শেষ হওয়ার পর বিজয়ী নির্বাচন করে এখানে রেজাল্ট জানিয়ে দেয়া হবে 
					</div>
				</div>
               <?php }else {
// 					echo "Group sizes:\n";
// 					echo "Group 1 size: $group_1_size\n";
// 					echo "Group 2 size: $group_2_size\n";
// 					echo "Group 3 size: $group_3_size\n";
// 					echo "Group 4 size: $group_4_size\n";
				} ?>
				
				
				
            </div>
        </li>
    </ul>

    <style>
        .custom-accordion {
            margin: 1rem 0;
            padding: 0;
            list-style: none;
            border-top: 1px solid #e5e5e5;
        }

        .custom-accordion-item {
            border-bottom: 1px solid #e5e5e5;
        }

        /* Thumb */
        .custom-accordion-thumb {
            margin: 0;
            padding: .8rem 0;
            cursor: pointer;
            font-weight: normal;

            // Chevron
            &::before {
                content: '';
                display: inline-block;
                height: 7px;
                width: 7px;
                margin-right: 1rem;
                margin-left: .5rem;
                vertical-align: middle;
                border-right: 1px solid;
                border-bottom: 1px solid;
                transform: rotate(-45deg);
                transition: transform .2s ease-out;
            }
        }

        /* Panel */
        .custom-accordion-panel {
            margin: 0;
            padding-bottom: .8rem;
            display: none;
        }

        /* Active */
        .custom-accordion-item.is-active {
            .custom-accordion-thumb::before {
                transform: rotate(45deg);
            }
        }
    </style>

<script>
    jQuery(document).ready(function ($) {
        // Open all accordion panels on load
        $(".custom-accordion > .custom-accordion-item").addClass("is-active").children(".custom-accordion-panel").slideDown(0); // Instant opening

        // Accordion toggle behavior on click
        $(".custom-accordion > .custom-accordion-item > .custom-accordion-thumb").click(function () {
            const parentItem = $(this).parent(); // Correctly select the parent item

            // Close all other accordion items
            parentItem.siblings(".custom-accordion-item").removeClass("is-active").children(".custom-accordion-panel").slideUp();

            // Toggle the current accordion item
            parentItem.toggleClass("is-active").children(".custom-accordion-panel").slideToggle("fast");
        });
    });
</script>


    <div class="message"></div>

</div>

<div class="right-part">
    <ul class="accordion">
        <li class="onexx accordion-item is-active">
            <h3 class="accordion-thumb">পুরস্কার:</h3>
            <div class="accordion-panel">
				
					<?php
					$gift_meta = get_post_meta( get_the_ID(), 'gift-1', true ); // Retrieve the 'gift-1' meta field value

					if ( ! empty( $gift_meta ) ) {
						echo $gift_meta ; // Display the 'gift-1' meta field value if it exists
					}
					?>

            </div>
        </li>

        <!--<li class="twoxx accordion-item">-->
        <!--    <h3 class="accordion-thumb">অংশগ্রহণের যোগ্যতা:</h3>-->
        <!--    <div class="accordion-panel">-->
	

				
				<?php
				//$gift_meta = get_post_meta( get_the_ID(), 'qu-1', true ); // Retrieve the 'gift-1' meta field value

				//if ( ! empty( $gift_meta ) ) {
					//echo  $gift_meta ; // Display the 'gift-1' meta field value if it exists
				//}
				?>

				
				
				
        <!--    </div>-->
        <!--</li>-->

        <!--<li class="threxx accordion-item">-->
        <!--    <h3 class="accordion-thumb">কুইজের কাঠামো:</h3>-->
        <!--      <div class="accordion-panel">-->

				  
				  
				  				
				<?php
				//$gift_meta = get_post_meta( get_the_ID(), 'qu-ka', true ); // Retrieve the 'gift-1' meta field value

				//if ( ! empty( $gift_meta ) ) {
				//	echo  $gift_meta; // Display the 'gift-1' meta field value if it exists
				//}
				?>

        <!--    </div>-->
        <!--</li>-->
    </ul>

    <style>
        .accordion {
            margin: 1rem 0;
            padding: 0;
            list-style: none;
            border-top: 1px solid #e5e5e5;
        }

        .accordion-item {
            border-bottom: 1px solid #e5e5e5;
        }

        /* Thumb */
        .accordion-thumb {
            margin: 0;
            padding: .8rem 0;
            cursor: pointer;
            font-weight: normal;

            // Chevron
            &::before {
                content: '';
                display: inline-block;
                height: 7px;
                width: 7px;
                margin-right: 1rem;
                margin-left: .5rem;
                vertical-align: middle;
                border-right: 1px solid;
                border-bottom: 1px solid;
                transform: rotate(-45deg);
                transition: transform .2s ease-out;
            }
        }

        /* Panel */
        .accordion-panel {
            margin: 0;
            padding-bottom: .8rem;
            display: none;
        }

        /* Active */
        .accordion-item.is-active {
            .accordion-thumb::before {
                transform: rotate(45deg);
            }
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {

            // (Optional) Active an item if it has the class "is-active"	
            $(".accordion > .accordion-item.is-active").children(".accordion-panel").slideDown();

            $(".accordion > .accordion-item").click(function () {
                // Cancel the siblings
                $(this).siblings(".accordion-item").removeClass("is-active").children(".accordion-panel").slideUp();
                // Toggle the item
                $(this).toggleClass("is-active").children(".accordion-panel").slideToggle("ease-out");
            });
        });
    </script>
</div>

<script>
    var timerWidget = function ($scope, $) {
        // Fetching values from data attributes using scoped $scope
        var $countdown = $scope.find('#countdown'); // Scoped to the current widget
        var totalSeconds = $countdown.data('value'); // Countdown duration in seconds
        var targetTime = $scope.find('#targetTime').data('value'); // Target start time from HTML
        var timezone = $scope.find('#timezone').data('value'); // Timezone from HTML
        var quizFormId = $scope.find('#quizFormId').data('value'); // Fetch quiz_form_id from HTML

        var countdownStarted = false; // To prevent multiple countdowns
		var countdownEnded = false; // To track if countdown has ended

        // Function to update the current time in the specified timezone
        function updateCurrentTime() {
            var now = new Date().toLocaleString("en-US", {
                timeZone: timezone,
                hour12: false,
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
            });

            // Adjust the format to YYYY-MM-DD HH:MM:SS for consistency
            var currentTime = now.replace(/(\d{2})\/(\d{2})\/(\d{4}),\s(\d{2}:\d{2}):(\d{2})/, '$3-$1-$2 $4:$5');
            $scope.find('#current-time').text(currentTime); // Scoped to the current widget

            checkTimeAndStartCountdown(currentTime);
        }

        // Function to check if the current time is between targetTime and targetTime + totalSeconds
        function checkTimeAndStartCountdown(currentTime) {
            var targetDateTime = new Date(targetTime); // Parse target time into Date object
            var endTime = new Date(targetDateTime.getTime() + totalSeconds * 1000); // Calculate end time

            var currentDateTime = new Date(currentTime); // Parse currentTime into Date object

            // Check if current time is between target time and end time
            if (!countdownStarted && currentDateTime >= targetDateTime && currentDateTime <= endTime) {
                $('body').removeClass('coming-soon').addClass('started');
                var remainingTime = Math.floor((endTime - currentDateTime) / 1000); // Calculate remaining time in seconds
                startCountdown(remainingTime);
                countdownStarted = true; // Mark countdown as started

                $('#ar-baki').show();
                $('#countdown').show();
                $('#countdown-coming').hide();
                $('#countdown-closed').hide();


                $('#quiz-form-container').show();
                $('#quiz-end').hide();
                $('#quiz-coming').hide();



                // If countdown is running, render the Formidable shortcode
                if (quizFormId) {
                    //$('#quiz-form-container').html('[formidable id=' + quizFormId + ']'); // Display shortcode
                }

                if (quizFormId) {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                        action: 'load_quiz_form',
                        quiz_form_id: quizFormId
                    },
                        success: function (response) {
//                             $('#quiz-form-container').html(response);
							$('#quiz-form-container').show();
                        }
			});
            }


        }else if (currentDateTime > endTime && !countdownEnded) {
                // Countdown has ended
                countdownEnded = true;

// 				clearInterval(countdownInterval);
                $countdown.html("00:00:00");
//                 $scope.find('.message').html("Countdown complete!");
                $('body').removeClass('started').addClass('ended'); // Add 'ended' class when countdown ends
                $('#ar-baki').hide();
                $('#countdown').hide();
                $('#countdown-coming').hide();
                $('#countdown-closed').show();
                $('#quiz-form-container').empty(); // Remove the form when countdown ends

                $('#quiz-form-container').hide();
                $('#quiz-end').show();
                $('#quiz-coming').hide();
				
            }
    }

    // Function to start the countdown with remaining time
    function startCountdown(remainingTime) {
        var countdownInterval = setInterval(function () {
            // Calculate hours, minutes, and seconds
            var hours = Math.floor(remainingTime / 3600);
            var minutes = Math.floor((remainingTime % 3600) / 60);
            var seconds = remainingTime % 60;

            // Display the countdown in the format HH:MM:SS
            $countdown.html(
                (hours > 0 ? String(hours).padStart(2, '0') + ":" : "") +
                String(minutes).padStart(2, '0') + ":" +
                String(seconds).padStart(2, '0')
            );

// 			alert(remainingTime);
            // Check if countdown has reached zero
            if (remainingTime <= 0) {
                clearInterval(countdownInterval);
                $countdown.html("00:00:00");
                $scope.find('.message').html("Countdown complete!");
                $('body').removeClass('started').addClass('ended'); // Add 'ended' class when countdown ends
                $('#ar-baki').hide();
                $('#countdown').hide();
                $('#countdown-coming').hide();
                $('#countdown-closed').show();
                $('#quiz-form-container').empty(); // Remove the form when countdown ends

                $('#quiz-form-container').hide();
                $('#quiz-end').show();
                $('#quiz-coming').hide();


            }

            remainingTime--;
        }, 1000); // Update every second
    }

    // Start with the 'coming-soon' class before countdown starts
//     $('body').addClass('coming-soon');
    $('#ar-baki').hide();
    $('#countdown').hide();
    $('#countdown-coming').show();
    $('#countdown-closed').hide();


    $('#quiz-form-container').hide();
    $('#quiz-end').hide();
    $('#quiz-coming').show();

    // Start updating the current time every second
    setInterval(updateCurrentTime, 1000);
};


    // Execute the widget code in the current scope (document ready)
    jQuery(document).ready(function ($) {
        timerWidget($(document), $);
    });
</script>
<?php
}


global $post; // Access the global $post variable
if (get_post_type($post) == 'user-list') {
// Query for the current post of post type 'user-list'
$args = array(
    'post_type' => 'user-list',
    'posts_per_page' => 1, // Only retrieve one post
    'p' => $post->ID, // Get the current post by ID
);

$user_list_posts = new WP_Query($args);
// var_dump($user_list_posts);

// Check if the post exists
if ($user_list_posts->have_posts()) {
    while ($user_list_posts->have_posts()) {
        $user_list_posts->the_post();
        // Check if the custom field 'new_account_agent' is set to '1'
      
            ?>
<div class="e-con-inn">

	<div class="ele-eading-default">
		<div class="search-cont">
					এটা আমাদের একমাত্র অফিসিয়াল এজেন্ট লিস্ট। এর বাইরে আমাদের কোন এজেন্ট নেই। লেনদেনের ক্ষেত্রে অবশ্যই মাস্টার এজেন্টদের নাম এবং হোয়াটসঅ্যাপ নাম্বার চেক করে নিন।
		</div>
	</div>
	
            <div class="list-box">
                <div class="focus-part">
                    <div class="user-id">
                        <p class="id-ti">আইডি</p>
                        <div class="id-value"><?php echo esc_html(get_post_meta(get_the_ID(), 'user_id', true)); ?></div>
                    </div>
                    <div class="name-dig">
                        <div class="img"><?php the_post_thumbnail(); ?></div>
                        <div class="title-cat">
                            <div class="title"><?php the_title(); ?></div>
                            <div class="cat-dig <?php echo esc_attr(implode(' ', get_post_class())); ?>">
                                <?php 
                                $postcat = get_the_category(get_the_ID());
                                if (isset($postcat[0])) {
                                    $postcat_name = $postcat[0]->name;
                                    // Output translated category names
                                    if ($postcat_name == "Customer Service") {
                                        echo "কাস্টমার সার্ভিস";
                                    } elseif ($postcat_name == "Sub Admin") {
                                        echo "সাব-এডমিন";
                                    } elseif ($postcat_name == "Admin") {
                                        echo "এডমিন";
                                    } elseif ($postcat_name == "Super Agent") {
                                        echo "সুপার এজেন্ট";
                                    } elseif ($postcat_name == "Master Agent") {
                                        echo "মাস্টার এজেন্ট";
                                    } else {
                                        echo "";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="social-connection-group" style="display: block">
                    <?php if (get_post_meta(get_the_ID(), 'whatsapp_primary', true) != '') { ?>
                        <div class="group-1">
                            <span class="value-1">WhatsApp <span>(Primary)</span></span>
                            <span class="value-2"><a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'whatsapp_primary_link', true)); ?>" target="_blank"><?php echo esc_html(get_post_meta(get_the_ID(), 'whatsapp_primary', true)); ?></a></span>
                            <span class="copy-btn">Copy Number</span>
                            <span class="value-4"><a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'whatsapp_primary_link', true)); ?>" target="_blank">Message</a></span>
                        </div>
                    <?php } ?>

                    <?php if (get_post_meta(get_the_ID(), 'whatsapp_secondary', true) != '') { ?>
                        <div class="group-2">
                            <span class="value-1">WhatsApp <span>(Secondary)</span></span>
                            <span class="value-2"><a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true)); ?>" target="_blank"><?php echo esc_html(get_post_meta(get_the_ID(), 'whatsapp_secondary', true)); ?></a></span>
                            <span class="copy-btn">Copy Number</span>
                            <span class="value-4"><a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'whatsapp_secondary_link', true)); ?>" target="_blank">Message</a></span>
                        </div>
                    <?php } ?>    

                    <?php if (get_post_meta(get_the_ID(), 'messenger', true) != '') { ?>
                        <div class="group-3">
                            <span class="value-1">Messenger</span>
                            <span class="value-2"><a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'messenger', true)); ?>" target="_blank"><?php echo esc_html(get_post_meta(get_the_ID(), 'messenger', true)); ?></a></span>
                            <span class="copy-btn">Copy</span>
                            <span class="value-4"><a href="<?php echo esc_url(get_post_meta(get_the_ID(), 'messenger', true)); ?>" target="_blank">Message</a></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
		
</div>
            <?php
        
    }
    wp_reset_postdata(); // Reset post data
} else {
//     echo '<p>No user found.</p>'; // Fallback message if no post is found
}

}


get_footer();