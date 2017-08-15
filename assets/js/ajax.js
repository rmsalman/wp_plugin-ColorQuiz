
jQuery(document).ready(function($) {

$('.q-nextz').click(function(){
setTimeout(function(){
$('.btn-two').css('pointer-events','none');
$('.btn-two').css('opacity','0.6');

// quiz number
var quiz_n = $('#quiz_no').val();
var quiz_result = $('#quiz_result').val();
console.log('here'+quiz_result);
// checking all questions
var array_q_ans0 = $('input[name="ques_id"]').map(function(){
            return parseInt($(this).val());
        }).get();
// checking all answers
var array_q_ans1 = $('input[type="radio"]:checked').map(function(){
            return parseInt($(this).attr('data-ans'));
        }).get();

var array_q_ans = [];
array_q_ans[0]= array_q_ans0;
array_q_ans[1]= array_q_ans1;

	jQuery.ajax({
		url: colorQuiz_ajax_url.ajax_url,
		type: 'post',
		data: {
			action : 'colorQuiz_ajax_function',
			q_ans : JSON.stringify(array_q_ans),
			quiz_no : quiz_n,
			result : quiz_result
		},
		success: function(response){
			$('.btn-two').css('pointer-events','all');
			$('.btn-two').css('opacity','1');
			jQuery('#ajax-response').html(response);
		}
	});
	}, 2000);
});


});