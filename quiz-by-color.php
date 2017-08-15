<?php 

/*
*	Plugin Name: Quiz By Color
*	Description: This plugin can create multiple Quizes, it can take user's answer with backend of color scheme.
*	Author: R. M. Salman Saeed
*	Author URI: https://www.linkedin.com/in/rmsalmansaeed/
*
*/




// create db table 
function create_colorQuiz_tables () {
	global $wpdb;
	$charset_collete = $wpdb -> get_charset_collate();
	$answers = $wpdb->prefix . 'answers';
	$questions = $wpdb->prefix . 'questions';
	$quizes = $wpdb->prefix . 'quizes';
	$results = $wpdb->prefix . 'results';
	$user_answers = $wpdb->prefix . 'user_answers';


	$sql_ans = "CREATE TABLE IF NOT EXISTS $answers (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qz_id` int(11) NOT NULL,
  `q_id` int(100) NOT NULL,
  `answer` text NOT NULL,
  `mark` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) $charset_collete;";

$sql_ques = "CREATE TABLE IF NOT EXISTS $questions (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `quiz_id` int(30) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) $charset_collete;";

$sql_quiz = "CREATE TABLE  IF NOT EXISTS $quizes  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) $charset_collete;";

$sql_results = "CREATE TABLE IF NOT EXISTS $results (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(30) NOT NULL,
  `quiz_id` int(30) NOT NULL,
  `your_type` text NOT NULL,
  `type_description` text NOT NULL,
  `offer` text NOT NULL,
  `offer_description` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) $charset_collete;
";

// $alter = "ALTER TABLE $quizes CHANGE `name` `name` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, CHANGE `title` `title` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL";

// $alter = "ALTER TABLE $user_answers ADD `quiz_result` INT(222) NOT NULL AFTER `answers`";
// $wpdb->query($alter);

$sql_user_answers = "CREATE TABLE $user_answers (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(200) NOT NULL,
  `quiz_id` int(200) NOT NULL,
  `questions` varchar(250) NOT NULL,
  `answers` varchar(250) NOT NULL,
  `quiz_result` INT(222) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) $charset_collete;
";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_ans);
	dbDelta($sql_ques);
	dbDelta($sql_quiz);
	dbDelta($sql_results);
	dbDelta($sql_user_answers);
}
register_activation_hook(__FILE__, 'create_colorQuiz_tables');

// Plugin Settings Page
add_action('admin_menu', 'quiz_plugin_settings');

function quiz_plugin_settings(){
	add_menu_page('Color',
					'Quiz', 
					'manage_options',
					'quiz-by-color',
					'colorQuiz_settings', 
					'dashicons-clipboard', 
					'90');

add_submenu_page( 'quiz-by-color', 
					'Add/Edit', 
					'Add/Edit',
				    'manage_options', 
				    'quiz-by-color'
			    );
add_submenu_page( 'quiz-by-color', 
					'User Answers', 
					'User Answers',
				    'manage_options', 
				    'colorQuiz-users',
				    'colorQuiz_users'
				  );
}

function colorQuiz_users() {
	// including main user file
	include_once( plugin_dir_path( __FILE__ ) . '/colorQuiz_users.php' );


}




function colorQuiz_settings() {

	global $wpdb;

	$answers = $wpdb->prefix . 'answers';
	$questions = $wpdb->prefix . 'questions';
	$quizes = $wpdb->prefix . 'quizes';
	$results = $wpdb->prefix . 'results';



// add quiz query
if(isset($_POST['quiz']) && !empty($_POST)){
	unset($_POST['quiz']);
	global $wpdb;
	$quizes = $wpdb->prefix . 'quizes';
$name = $_POST['name'];
$title = $_POST['title'];
$description = $_POST['description'];
	$wpdb->query("INSERT INTO $quizes 
				(`name`, `title`, `description`) 
				VALUES 
				('$name','$title','$description')");


 //   $wpdb->show_errors(); 
 //   $wpdb->print_error();
}


// delete quiz 


if(isset($_GET['delete_quiz']) && !empty($_GET['delete_quiz'])){
	
	global $wpdb;
	$quizes = $wpdb->prefix . 'quizes';
$delete_quiz = $_GET['delete_quiz'];
	$wpdb->query("DELETE FROM $quizes WHERE id = '$delete_quiz'");
}


// delete answer


if(isset($_GET['delete_ans']) && !empty($_GET['delete_ans'])){
	
	global $wpdb;
	$answers = $wpdb->prefix . 'answers';
	$delete_ans = $_GET['delete_ans'];
	$wpdb->query("DELETE FROM $answers WHERE id = '$delete_ans'");
}



// delete question


if(isset($_GET['delete_ques']) && !empty($_GET['delete_ques'])){
	
	global $wpdb;
	$questions = $wpdb->prefix . 'questions';
	$delete_ques = $_GET['delete_ques'];
	$wpdb->query("DELETE FROM $questions WHERE id = '$delete_ques'");
}

// delete question


if(isset($_GET['delete_res']) && !empty($_GET['delete_res'])){
	
	global $wpdb;
	$results = $wpdb->prefix . 'results';
	$delete_res = $_GET['delete_res'];
	$wpdb->query("DELETE FROM $results WHERE id = '$delete_res'");
}



// adding color pattern or color responses


if(isset($_POST['resultent'])){
	$results = $wpdb->prefix . 'results'; 
	$result_id = $_POST['result_id'];
	$quiz_id = $_POST['quiz_id'];
	$your_type = $_POST['your_type'];
	$type_description = $_POST['type_description'];
	$offer = $_POST['offer'];
	$offer_description = $_POST['offer_description'];


	$wpdb->query("INSERT INTO $results 
				(`result_id`, `quiz_id`, `your_type`, `type_description`, `offer`, `offer_description`) 
				VALUES 
				('$result_id','$quiz_id','$your_type','$type_description','$offer','$offer_description')");

}

if(isset($_POST['edit_quiz'])){
	$quizes = $wpdb->prefix . 'quizes'; 
	$name = $_POST['name'];
	$description = $_POST['description'];
	$title = $_POST['title'];
	$id = $_POST['id'];

	$wpdb->query("UPDATE $quizes SET `description` = '$description', `name` = '$name', `title` = '$title'  WHERE `id` =".$id);
}



if(isset($_POST['edit_ques'])){
	$questions = $wpdb->prefix . 'questions'; 
	$question = $_POST['question'];
	$id = $_POST['id'];

	$wpdb->query("UPDATE $questions SET `question` = '$question' WHERE `id` =".$id);
}



if(isset($_POST['edit_ans'])){
	$answers = $wpdb->prefix . 'answers'; 
	$answer = $_POST['answer'];
	$mark = $_POST['mark'];
	$id = $_POST['id'];

	$wpdb->query("UPDATE $answers SET `answer` = '$answer', `mark` = '$mark' WHERE `id` =".$id);

   // $wpdb->show_errors(); 
   // $wpdb->print_error();
   // die;
}


if(isset($_POST['add_ans'])){

	$answers = $wpdb->prefix . 'answers'; 
	$answer = $_POST['answer'];
	$mark = $_POST['mark'];
	$quiz_id = $_POST['quiz_id'];
	$ques_id = $_POST['ques_id'];

	$wpdb->query("INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`, `created`, `updated`) VALUES ('$quiz_id','$ques_id','$answer','$mark', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
// $wpdb->show_errors(); 
//    $wpdb->print_error();
//    die;
}



if(isset($_POST['result_edit'])){
	$results = $wpdb->prefix . 'results'; 
	$id = $_POST['id'];
	$your_type = $_POST['your_type'];
	$type_description = $_POST['type_description'];
	$offer = $_POST['offer'];
	$offer_description = $_POST['offer_description'];
	$result_id = $_POST['result_id'];

	$wpdb->query("UPDATE $results SET `your_type` = '$your_type', `type_description` = '$type_description', `offer` = '$offer', `offer_description` = '$offer_description', `result_id` = '$result_id' WHERE `id` =".$id);

   // $wpdb->show_errors(); 
   // $wpdb->print_error();
   // die;
}



?>
<style>
	.view_qs ul {
    margin-left: 15px;
}
.view_qs {
    border-bottom: 1px solid;
}
.add_more_ans {
    position: relative;
    top: 49px;
}

</style>
<div class="container-fluid">
<h1>Quiz Add/Edit</h1>
<hr>

<?php
	$quizes = $wpdb->prefix . 'quizes';

$sql_quizes = "SELECT * FROM $quizes";
$results = $wpdb->get_results($sql_quizes);

if(!empty($results)){

if(isset($_GET['add_quiz'])){
	// add quiz on click
 ?>

<form action="<?= admin_url(); ?>?page=quiz-by-color" method="POST">
	
  <div class="form-group">
    <label for="name">Name of Quiz</label>
	<input type="text" class="form-control" name="name" id="name">
	</div>
  <div class="form-group">
	<label for="title">Title</label>
	<input type="text" class="form-control" name="title" id="title">
	</div>
  <div class="form-group">
	<label for="description">Description</label>
	<input class="form-control" type="text" name="description" id="description">
	</div>
	<input type="submit" class="btn btn-default" name="quiz">
</form>

<?php }else { 

// show quiz
	?>
<br>
   <a  <?php if (isset($_GET['hide_quiz'])){echo 'style="display: none;"';} ?> href="<?= admin_url(); ?>?page=quiz-by-color&add_quiz" class="btn btn-default pull-right">Add Quiz</a>

   <a style="display:<?php if (isset($_GET['hide_quiz'])){echo 'inline-block';}else { echo 'none';}?>" href="<?= admin_url(); ?>?page=quiz-by-color" class="btn btn-default pull-">Home</a>

   <span class="clearfix"></span>
   <br>
<table class="table table-bordered table-hover " <?php if (isset($_GET['hide_quiz'])){echo 'hidden';} ?> >
	<thead>
		<tr>
			<th>
				Short Code
			</th>
			<th>
				Name
			</th>
			<th>
				Description
			</th>
			<th>
				Questions
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach( $results as $result ) {
    	?>
		<tr>
			<td>[colorQuiz quiz="<?= $result->id; ?>"]</td>
			<td><?= $result->name; ?></td>
			<td><?= $result->description; ?></td>
			<td>
				<a class="btn btn-default" href="<?= admin_url(); ?>?page=quiz-by-color&add_qs=<?= $result->id; ?>&hide_quiz=1">Add MCQs</a>

				<a class="btn btn-default" href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $result->name; ?>&view_qs=<?= $result->id; ?>&hide_quiz=1">View MCQs</a>

				<a class="btn btn-default" href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $result->name; ?>&color_pattern_view=<?= $result->id; ?>&hide_quiz=1">View Color Response</a>

				<a class="btn btn-default" href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $result->name; ?>&edit_quiz=<?= $result->id; ?>&hide_quiz=1">Edit Quiz</a>

				<a class="btn btn-default" href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $result->name; ?>&delete_quiz=<?= $result->id; ?>" onclick="return confirm(' you want to delete?');">Delete Quiz</a>
			</td>
		</tr>
		<?php
    }
	?>
	</tbody>
</table>
<?php 
}
}
else {
// add first quiz form
	?>


<h1>Add Your First Quiz</h1>

<form action="<?= admin_url(); ?>?page=quiz-by-color" method="POST">
	
  <div class="form-group">
  <label for="name">Name of Quiz</label>
	<input type="text" class="form-control" name="name" id="name">
	</div>
  <div class="form-group">
	<label for="title">Title</label>
	<input type="text" class="form-control" name="title" id="title">
	</div>
  <div class="form-group">
	<label for="description">Description</label>
	<input class="form-control" type="text" name="description" id="description">
	</div>
	<input type="submit" class="btn btn-default" name="quiz">
</form>


	<?php
}

// add quizes
if(isset($_GET['add_qs']) && !empty($_GET['add_qs'])){
	$add_qs = $_GET['add_qs'];
?>
<h2>Adding Question and Answers to Quiz # <?= $_GET['add_qs'] ?> <a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=salman&view_qs=<?= $_GET['add_qs'] ?>&hide_quiz=1" class="btn btn-default pull-right">View All MCQs</a></h2>
<form action="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=salman&view_qs=<?= $_GET['add_qs'] ?>&hide_quiz=1" method="POST">
  <div class="form-group">
    <label for="question">Question</label>
    <input type="text" name="question" class="form-control" id="question" placeholder="Question">
  </div>

	<input type="hidden" name="quiz_id" value="<?= $_GET['add_qs']; ?>">

  <div class="form-group">
    <label for="answer_1">Answer #1</label>
    <input type="text" name="answer_1" class="form-control" required id="answer_1" placeholder="answer #1">
    <div class="mark">
	    <label for="answer_1_mark"><input type="radio" checked="checked" name="answer_1_mark" value="0" id="answer_1_mark"> Yellow(0)</label>	
	    <label for="answer_1_mark_2"><input type="radio" name="answer_1_mark" value="1" id="answer_1_mark_2" > Purple(1)</label>	
	    <label for="answer_1_mark_3"><input type="radio" name="answer_1_mark" value="2" id="answer_1_mark_3"> Green(2)</label>	
	    <label for="answer_1_mark_4"><input type="radio" name="answer_1_mark" value="3" id="answer_1_mark_4"> orange(3)</label>	
	    <label for="answer_1_mark_5"><input type="radio" name="answer_1_mark" value="4" id="answer_1_mark_5"> grey(4)</label>	
    </div>

    <div class="add_more"><span class="btn btn-primary add_more_ans pull-right">Add More Answers</span>
	<span class="clearfix"></span>
  </div>
  </div>
  <div class="form-group" style="display: none">
    <label for="answer_2">Answer #2</label>
    <input type="text" name="answer_2" class="form-control" id="answer_2" placeholder="answer #2">
  	   <div class="mark">
	    <label for="answer_2_mark"><input type="radio" checked="checked" name="answer_2_mark" value="0" id="answer_2_mark"> Yellow(0)</label>	
	    <label for="answer_2_mark_2"><input type="radio" name="answer_2_mark" value="1" id="answer_2_mark_2" > Purple(1)</label>	
	    <label for="answer_2_mark_3"><input type="radio" name="answer_2_mark" value="2" id="answer_2_mark_3"> Green(2)</label>	
	    <label for="answer_2_mark_4"><input type="radio" name="answer_2_mark" value="3" id="answer_2_mark_4"> orange(3)</label>	

	    <label for="answer_2_mark_5"><input type="radio" name="answer_2_mark" value="4" id="answer_2_mark_5"> grey(4)</label>	
    </div>

    <div class="add_more"><span class="btn btn-primary add_more_ans pull-right">Add More Answers</span>
	<span class="clearfix"></span>
    </div>
  </div>
  <div class="form-group" style="display: none;">
    <label for="answer_3">Answer #3</label>
    <input type="text" name="answer_3" class="form-control" id="answer_3" placeholder="answer #3">
       <div class="mark">
	    <label for="answer_3_mark"><input type="radio" checked="checked" name="answer_3_mark" value="0" id="answer_3_mark"> Yellow(0)</label>	
	    <label for="answer_3_mark_2"><input type="radio" name="answer_3_mark" value="1" id="answer_3_mark_2" > Purple(1)</label>	
	    <label for="answer_3_mark_3"><input type="radio" name="answer_3_mark" value="2" id="answer_3_mark_3"> Green(2)</label>	
	    <label for="answer_3_mark_4"><input type="radio" name="answer_3_mark" value="3" id="answer_3_mark_4"> orange(3)</label>	

	    <label for="answer_3_mark_5"><input type="radio" name="answer_3_mark" value="4" id="answer_3_mark_5"> grey(4)</label>	
    </div>

    <div class="add_more"><span class="btn btn-primary add_more_ans pull-right">Add More Answers</span>
	<span class="clearfix"></span>
    </div>
  </div>
  <div class="form-group" style="display: none;">
    <label for="answer_4">Answer #4</label>
    <input type="text" name="answer_4" class="form-control" id="answer_4" placeholder="answer #4">
       <div class="mark">
	    <label for="answer_4_mark"><input type="radio" checked="checked" name="answer_4_mark" value="0" id="answer_4_mark"> Yellow(0)</label>	
	    <label for="answer_4_mark_2"><input type="radio" name="answer_4_mark" value="1" id="answer_4_mark_2" > Purple(1)</label>	
	    <label for="answer_4_mark_3"><input type="radio" name="answer_4_mark" value="2" id="answer_4_mark_3"> Green(2)</label>	
	    <label for="answer_4_mark_4"><input type="radio" name="answer_4_mark" value="3" id="answer_4_mark_4"> orange(3)</label>	

	    <label for="answer_4_mark_5"><input type="radio" name="answer_4_mark" value="4" id="answer_4_mark_5"> grey(4)</label>	
    </div>
    <div class="add_more"><span class="btn btn-primary add_more_ans pull-right">Add More Answers</span>
	<span class="clearfix"></span>
    </div>
  </div>
  <div class="form-group" style="display: none;">
    <label for="answer_5">Answer #5</label>
    <input type="text" name="answer_5" class="form-control" id="answer_5" placeholder="answer #5">
       <div class="mark">
	    <label for="answer_5_mark"><input type="radio" checked="checked" value="1" name="answer_5_mark"  id="answer_5_mark"> Yellow(0)</label>	
	    <label for="answer_5_mark_2"><input type="radio" name="answer_5_mark" value="1" id="answer_5_mark_2" > Purple(1)</label>	
	    <label for="answer_5_mark_3"><input type="radio" name="answer_5_mark" value="2" id="answer_5_mark_3"> Green(2)</label>	
	    <label for="answer_5_mark_4"><input type="radio" name="answer_5_mark" value="3" id="answer_5_mark_4"> orange(3)</label>	

	    <label for="answer_5_mark_5"><input type="radio" name="answer_5_mark" value="4" id="answer_5_mark_5"> grey(4)</label>	
    </div>
    <div class="add_more"><span class="btn btn-primary add_more_ans pull-right">Add More Answers</span>
	<span class="clearfix"></span>
    </div>
  </div>
  <div class="form-group" style="display: none;">
    <label for="answer_6">Answer #6</label>
    <input type="text" name="answer_6" class="form-control" id="answer_6" placeholder="answer #6">
       <div class="mark">
	    <label for="answer_6_mark"><input type="radio" checked="checked" value="1" name="answer_6_mark"  id="answer_6_mark"> Yellow(0)</label>	
	    <label for="answer_6_mark_2"><input type="radio" name="answer_6_mark" value="1" id="answer_6_mark_2" > Purple(1)</label>	
	    <label for="answer_6_mark_3"><input type="radio" name="answer_6_mark" value="2" id="answer_6_mark_3"> Green(2)</label>	
	    <label for="answer_6_mark_4"><input type="radio" name="answer_6_mark" value="3" id="answer_6_mark_4"> orange(3)</label>	

	    <label for="answer_6_mark_5"><input type="radio" name="answer_6_mark" value="4" id="answer_6_mark_5"> grey(4)</label>	
    </div>
    <div class="add_more"><span class="btn btn-primary add_more_ans pull-right">Add More Answers</span>
	<span class="clearfix"></span>
    </div>
  </div>
  <button type="submit" class="btn btn-default" name="qs_1">Submit</button>
</form>
<?php
}

 ?>




<?php 
// view questions of quizes




if(isset($_GET['view_qs'])){



//  add questions and answers quries
if(isset($_POST['qs_1'])){

	$quizes = $wpdb->prefix . 'quizes'; 
	$quiz_id = $_POST['quiz_id'];
	$question_title = $_POST['question'];


	$wpdb->query("INSERT INTO $questions 
					(`question`, `quiz_id`) 
					VALUES 
					('$question_title', '$quiz_id')");

	$q_id = $wpdb->insert_id;

$answer_1 		= $_POST['answer_1'];
$answer_2 		= $_POST['answer_2'];
$answer_3 		= $_POST['answer_3'];
$answer_4 		= $_POST['answer_4'];
$answer_5 		= $_POST['answer_5'];
$answer_6 		= $_POST['answer_6'];
$answer_1_mark 	= $_POST['answer_1_mark'];
$answer_2_mark 	= $_POST['answer_2_mark'];
$answer_3_mark 	= $_POST['answer_3_mark'];
$answer_4_mark 	= $_POST['answer_4_mark'];
$answer_5_mark 	= $_POST['answer_5_mark'];
$answer_6_mark 	= $_POST['answer_6_mark'];


	if(isset($_POST['answer_6']) && !empty($_POST['answer_6'])){

$sql_insert_a 	= "INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`) VALUES 
				 ($quiz_id, $q_id, '$answer_1', $answer_1_mark),
				 ($quiz_id, $q_id, '$answer_2', $answer_2_mark),
				 ($quiz_id, $q_id, '$answer_3', $answer_3_mark),
				 ($quiz_id, $q_id, '$answer_4', $answer_4_mark),
				 ($quiz_id, $q_id, '$answer_5', $answer_5_mark),
				 ($quiz_id, $q_id, '$answer_6', $answer_6_mark)";

	}
	elseif (isset($_POST['answer_5']) && !empty($_POST['answer_5'])) {

$sql_insert_a 	= "INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`) VALUES 
				 ($quiz_id, $q_id, '$answer_1', $answer_1_mark),
				 ($quiz_id, $q_id, '$answer_2', $answer_2_mark),
				 ($quiz_id, $q_id, '$answer_3', $answer_3_mark),
				 ($quiz_id, $q_id, '$answer_4', $answer_4_mark),
				 ($quiz_id, $q_id, '$answer_5', $answer_5_mark)";
	}
	elseif (isset($_POST['answer_4']) && !empty($_POST['answer_4'])) {

$sql_insert_a 	= "INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`) VALUES 
				 ($quiz_id, $q_id, '$answer_1', $answer_1_mark),
				 ($quiz_id, $q_id, '$answer_2', $answer_2_mark),
				 ($quiz_id, $q_id, '$answer_3', $answer_3_mark),
				 ($quiz_id, $q_id, '$answer_4', $answer_4_mark)";
	}
	elseif (isset($_POST['answer_3']) && !empty($_POST['answer_3'])) {

$sql_insert_a 	= "INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`) VALUES 
				 ($quiz_id, $q_id, '$answer_1', $answer_1_mark),
				 ($quiz_id, $q_id, '$answer_2', $answer_2_mark),
				 ($quiz_id, $q_id, '$answer_3', $answer_3_mark)";
	}
	elseif (isset($_POST['answer_2']) && !empty($_POST['answer_2']))  {

$sql_insert_a 	= "INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`) VALUES 
				 ($quiz_id, $q_id, '$answer_1', $answer_1_mark),
				 ($quiz_id, $q_id, '$answer_2', $answer_2_mark)";
	}
	else {

$sql_insert_a 	= "INSERT INTO $answers (`qz_id`, `q_id`, `answer`, `mark`) VALUES 
				 ($quiz_id, $q_id, '$answer_1', $answer_1_mark)";
	}

		$wpdb->query($sql_insert_a);
   // $wpdb->show_errors(); 
   // $wpdb->print_error();
}



?>



<div class="view_quiz_qns">

	<h2>View All Quiz Questions and answer of Quiz(<span><?= $_GET['view_qs_title']; ?></span>) <a href="<?= admin_url(); ?>?page=quiz-by-color&add_qs=<?= $_GET['view_qs'] ?>&hide_quiz=1" class="btn btn-default pull-right">Add more Questions</a>
<span class="clearfix"></span></h2>
<?php 

$questions = $wpdb->prefix . 'questions';
$answers   = $wpdb->prefix . 'answers';
$qz_id = $_GET['view_qs'];
$sql_qna = "SELECT * FROM $questions where quiz_id = $qz_id";
$results = $wpdb->get_results($sql_qna);

if(!empty($results)){

	foreach( $results as $result ) {
 ?>
	<div class="view_qs">
		<h3><?= $result -> question; ?> 

		<a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $result -> question ; ?>&add_ans=<?= $result->id; ?>&hide_quiz=1&quiz_id=<?= $_GET['view_qs']?>" class="btn btn-primary">Add Answers</a>

		<a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $_GET['view_qs_title']; ?>&edit_ques=<?= $result->id; ?>&hide_quiz=1&quiz_id=<?= $_GET['view_qs']?>" class="btn btn-primary">Edit</a>

		<a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs=<?= $_GET['view_qs']?>&view_qs_title=<?= $_GET['view_qs_title']; ?>&hide_quiz=1&delete_ques=<?= $result->id; ?>" onclick="return confirm(' Are you sure want to delete this Question?');" class="btn btn-primary">Delete</a></h3>

		<ul>
		<?php 
$q_id = $result -> id;

$sql_ans = "SELECT * FROM $answers where q_id = $q_id";
$results_ans = $wpdb->get_results($sql_ans);
if(!empty($results_ans)){

	foreach( $results_ans as $result_ans ) {

// yellow(0)
// purple(1)
// green (2)
// orange(3)
if($result_ans -> mark == 0) {
	$mark = 'yellow';
}elseif ($result_ans -> mark == 1) {
	$mark = 'purple';
}elseif ($result_ans -> mark == 2) {
	$mark = 'green';
}elseif ($result_ans -> mark == 3) {
	$mark = 'orange';
}else {
	$mark = 'grey';
}

		 ?>
			<li><?= $result_ans -> answer ?> <span class="mark color-<?= $mark; ?>"><i><?= $mark; ?></i> </span>  
			<a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $_GET['view_qs_title']; ?>&edit_ans=<?= $result_ans->id; ?>&hide_quiz=1&quiz_id=<?= $_GET['view_qs']?>&question_title=<?= $result -> question ?>" class="btn btn-primary">Edit</a> 
			<a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs=<?= $_GET['view_qs']?>&view_qs_title=<?= $_GET['view_qs_title']; ?>&hide_quiz=1&delete_ans=<?= $result_ans->id; ?>" onclick="return confirm(' Are you sure want to delete this Answer?');" class="btn btn-primary">Delete</a></li>

		<?php 
	}
}else {
	?>
	<li>Sorry no answer found against this Question.</li>
	<?php
}
		 ?>
		</ul>
	</div>
<?php 
}
}else {
	?>
<center><h4>Sorry no Question found</h4></center>
	<?php
	} ?>

</div>

<?php
}
 ?>


<?php 
// adding color pattern to quiz
if(isset($_GET['color_pattern'])){

// yellow(0)
// purple(1)
// green (2)
// orange(3)
?>

<div class="view_quiz_qns">
	<h2>Adding color Responses to Quiz(<span><?= $_GET['view_qs_title']; ?></span>)</h2>

	<form action="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $_GET['view_qs_title']; ?>&color_pattern_view=<?= $_GET['color_pattern']; ?>&hide_quiz=1" method="POST">
		<input type="hidden" value="<?= $_GET['color_pattern']; ?>" name="quiz_id">
  	<div class="form-group">
	    <label for="result_id">Result Id</label>
	    <select name="result_id" class="form-control" required id="result_id"> 
	    	<option value="0" selected="selected">yellow</option> 
	    	<option value="1">purple</option> 
	    	<option value="2">green</option> 
	    	<option value="3">orange</option> 
	    	<option value="4">grey(neutral)</option> 
	    </select>
    </div>
    <div class="form-group">
	    <label for="your_type">Your Type</label>
	    <input type="text" name="your_type" class="form-control" required id="your_type" placeholder="Your Type"> 
    </div>
    <div class="form-group">
	    <label for="type_description">Type Description</label>
	    <textarea name="type_description" class="form-control" required id="type_description" placeholder="Type Description"></textarea>
    </div>
    <div class="form-group">
	    <label for="offer">Offer</label>
	    <input type="text" name="offer" class="form-control" required id="offer" placeholder="Offer">
    </div>
    <div class="form-group">
	    <label for="offer_description">Offer Description</label>
	    <textarea name="offer_description" class="form-control" required id="offer_description" placeholder="Offer Description"></textarea>
    </div>

  <button type="submit" class="btn btn-default" name="resultent">Submit</button>

	</form>

<?php 
}
?>


<?php 
if(isset($_GET['color_pattern_view'])){

?>
<div class="view_quiz_qns">
	<h2>Color Response of Quiz(<span><?= $_GET['view_qs_title']; ?></span>) <a class="btn btn-default pull-right" href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $_GET['view_qs_title']; ?>&color_pattern=<?= $_GET['color_pattern_view']; ?>&hide_quiz=1">Add Color Response</a></h2>
<?php 

$results = $wpdb->prefix . 'results';
$answers   = $wpdb->prefix . 'answers';
$qz_id = $_GET['color_pattern_view'];
$sql_clr = "SELECT * FROM $results where quiz_id = $qz_id";
$results_clr = $wpdb->get_results($sql_clr);
?>

	<table class="table table-default">
		<thead>
			<tr>
				<th>
					Result Color
				</th>
				<th>
					Your Type 
				</th>
				<th>
					Type Description 
				</th>
				<th>
					Offer 
				</th>
				<th>
					Offer description 
				</th>
				<th>
					Actions 
				</th>
			</tr>
		</thead>
		<tbody>
		<?php 

if(!empty($results_clr)){

	foreach( $results_clr as $result_clr ) {

// yellow(0)
// purple(1)
// green (2)
// orange(3)
if($result_clr -> result_id == 0) {
	$mark = 'yellow';
}elseif ($result_clr -> result_id == 1) {
	$mark = 'purple';
}elseif ($result_clr -> result_id == 2) {
	$mark = 'green';
}elseif ($result_clr -> result_id == 3) {
	$mark = 'orange';
}else {
	$mark = 'grey';
}

		 ?>
			<tr>
				<td><?= $mark; ?></td>
				<td><?= $result_clr -> your_type; ?></td>
				<td><?= $result_clr -> type_description; ?></td>
				<td><?= $result_clr -> offer; ?></td>
				<td><?= $result_clr -> offer_description; ?></td>
				<td>
					
			<a href="<?= admin_url(); ?>?page=quiz-by-color&color_pattern_viewz=<?= $_GET['color_pattern_view']; ?>&view_qs_title=<?= $_GET['view_qs_title']; ?>&edit_res=<?= $result_clr->id; ?>&hide_quiz=1" class="btn btn-primary">Edit</a>

			<a href="<?= admin_url(); ?>?page=quiz-by-color&view_qs_title=<?= $_GET['view_qs_title']; ?>&color_pattern_view=<?= $_GET['color_pattern_view']; ?>&hide_quiz=1&delete_res=<?= $result_clr->id; ?>" onclick="return confirm(' Are you sure want to delete this Answer?');" class="btn btn-primary">Delete</a>

				</td>
			</tr>
			<?php
}
}
else {
?>
<tr>
<td>No Color Response Found</td>
</tr>
<?php
}
?>
		</tbody>
	</table>


<?php
}

 ?>





<?php 
// edit quiz
if(isset($_GET['edit_quiz']) && !empty($_GET['edit_quiz'])){

			$quizes = $wpdb->prefix . 'quizes';
			$sql_get_quiz = "SELECT * FROM $quizes where id = ".$_GET['edit_quiz'];
			$results = $wpdb->get_row($sql_get_quiz);

			?>

<form action="<?= admin_url(); ?>?page=quiz-by-color" method="POST">
  <div class="form-group">
  <input type="hidden" name="id" value="<?= $results->id?>">
    <label for="name">Name of Quiz</label>
	<input type="text" class="form-control" name="name" id="name" value="<?= $results->name?>">
	</div>
  <div class="form-group">
	<label for="title">Title</label>
	<input type="text" class="form-control" name="title" id="title" value="<?= $results->title?>">
	</div>
  <div class="form-group">
	<label for="description">Description</label>
	<input class="form-control" type="text" name="description" id="description" value="<?= $results->description;?>">
	</div>
	<input type="submit" class="btn btn-default" name="edit_quiz">
</form>

<?php
}

// edit quiz
if(isset($_GET['edit_ques']) && !empty($_GET['edit_ques'])){

			$questions = $wpdb->prefix . 'questions';
			$sql_get_quiz = "SELECT * FROM $questions where id = ".$_GET['edit_ques'];
			$results = $wpdb->get_row($sql_get_quiz);
?>

<h2>Edit Question of Quiz(<span><?= $_GET['view_qs_title']; ?></span>)</h2>
<form action="<?= admin_url(); ?>?page=quiz-by-color&view_qs=<?= $_GET['quiz_id']?>&view_qs_title=<?= $_GET['view_qs_title']?>&hide_quiz=1" method="POST">
  <div class="form-group">
  <input type="hidden" name="id" value="<?= $results->id?>">
    <label for="question">Title of Question</label>
	<input type="text" class="form-control" name="question" id="question" value="<?= $results->question?>">
  </div>
	<input type="submit" class="btn btn-default" name="edit_ques">
</form>

<?php
}

// edit Answer
if(isset($_GET['edit_ans']) && !empty($_GET['edit_ans'])){

			$answers = $wpdb->prefix . 'answers';
			$sql_get_quiz = "SELECT * FROM $answers where id = ".$_GET['edit_ans'];
			$results = $wpdb->get_row($sql_get_quiz);
?>
<h2>Edit Answer of Question(<span><?= $_GET['question_title']; ?></span>)</h2>
<form action="<?= admin_url(); ?>?page=quiz-by-color&view_qs=<?= $_GET['quiz_id']?>&view_qs_title=<?= $_GET['view_qs_title']?>&hide_quiz=1" method="POST">
  <div class="form-group">
  <input type="hidden" name="id" value="<?= $results->id?>">
    <label for="answer">Title of Answer</label>
	<input type="text" class="form-control" required name="answer" id="answer" value="<?= $results->answer?>">
	</div>
  
  <div class="form-group">
	<label for="mark">Color</label>
	<select name="mark" class="form-control" required id="mark"> 
	    	<option value="0" selected="selected">yellow</option> 
	    	<option value="1">purple</option> 
	    	<option value="2">green</option> 
	    	<option value="3">orange</option> 
	    	<option value="4">grey(neutral)</option> 
	    </select>
	</div>
	<input type="submit" class="btn btn-default" name="edit_ans">
</form>

<?php
}

// Add single Answer
if(isset($_GET['add_ans']) && !empty($_GET['quiz_id'])){
?>
<h2>Add Answer to Question(<span><?= $_GET['view_qs_title']; ?></span>)</h2>
<form action="<?= admin_url(); ?>?page=quiz-by-color&view_qs=<?= $_GET['quiz_id']?>&view_qs_title=<?= $_GET['view_qs_title']?>&hide_quiz=1" method="POST">
  <div class="form-group">
  <input type="hidden" name="quiz_id" value="<?= $_GET['quiz_id']; ?>">
  <input type="hidden" name="ques_id" value="<?= $_GET['add_ans']; ?>">
    <label for="answer">Title of Answer</label>
	<input type="text" class="form-control" required name="answer" id="answer" value="">
	</div>
  
  <div class="form-group">
	<label for="mark">Color</label>
	<select name="mark" class="form-control" required id="mark"> 
	    	<option value="0" selected="selected">yellow</option> 
	    	<option value="1">purple</option> 
	    	<option value="2">green</option> 
	    	<option value="3">orange</option> 
	    	<option value="4">grey(neutral)</option> 
	    </select>
	</div>
	<input type="submit" class="btn btn-default" name="add_ans">
</form>

<?php
}

// edit Color Response
if(isset($_GET['edit_res']) && !empty($_GET['edit_res'])){
			$results = $wpdb->prefix . 'results';
			$sql_get_quiz = "SELECT * FROM $results where id = ".$_GET['edit_res'];
			$results = $wpdb->get_row($sql_get_quiz);
?>
<h2>Edit Response of Quiz(<span><?= $_GET['view_qs_title']; ?></span>)</h2>
	<form action="<?= admin_url(); ?>
	?page=quiz-by-color&view_qs_title=<?= $_GET['view_qs_title']?>&color_pattern_view=<?= $_GET['color_pattern_viewz']?>&hide_quiz=1" method="POST">
		<input type="hidden" value="<?= $results-> id ?>" name="id">
  	<div class="form-group">
	    <label for="result_id">Result Id</label>
	    <select name="result_id" class="form-control" required="" id="result_id"> 
	    	<option value="0" selected="selected">yellow</option> 
	    	<option value="1">purple</option> 
	    	<option value="2">green</option> 
	    	<option value="3">orange</option> 
	    	<option value="4">grey(neutral)</option> 
	    </select>
    </div>
    <div class="form-group">
	    <label for="your_type">Your Type</label>
	    <input type="text" value="<?= $results-> your_type ?>" name="your_type" class="form-control" required="" id="your_type" placeholder="Your Type"> 
    </div>
    <div class="form-group">
	    <label for="type_description">Type Description</label>
	    <textarea name="type_description" class="form-control" required="" id="type_description" placeholder="Type Description"><?= $results-> type_description ?></textarea>
    </div>
    <div class="form-group">
	    <label for="offer">Offer</label>
	    <input type="text" name="offer"  value="<?= $results-> offer ?>" class="form-control" required="" id="offer" placeholder="Offer">
    </div>
    <div class="form-group">
	    <label for="offer_description">Offer Description</label>
	    <textarea name="offer_description" class="form-control" required="" id="offer_description" placeholder="Offer Description"><?= $results-> offer_description ?></textarea>
    </div>

  <button type="submit" class="btn btn-default" name="result_edit">Submit</button>

	</form>

<?php
}
?>


<script>
(function($) {


function delete_quiz() {
    if (confirm("Are you sure?")) {
        // your deletion code
    }
    return false;
}

	$('.add_more_ans').click(function(){
		if($(this).parent().parent().find('input').val()){
	$(this).parent().parent().find('input').attr('required', 'required')
	 		$(this).parent().parent().next().show();
		 	$(this).hide();	
		}
	});
})( jQuery );
</script>

</div> 
<!-- end of Container div -->


<?php
}

// including main user file
include_once( plugin_dir_path( __FILE__ ) . '/user_side.php' );

function add_ajax_file(){
	wp_enqueue_script('colorQuiz-ajax', plugins_url('/assets/js/ajax.js',__FILE__), array('jquery'), true);
	wp_localize_script('colorQuiz-ajax', 'colorQuiz_ajax_url', array(
			'ajax_url'=> admin_url('admin-ajax.php')
		));
}

function load_custom_plugin_scripts(){
	wp_register_style('custom_plugin_css', plugin_dir_url(__FILE__).'/assets/css/bootstrap.min.css', false, '1.0.0');
	wp_enqueue_style('custom_plugin_css');
}

function load_custom_plugin_scripts2(){
	wp_register_style('main_css', plugin_dir_url(__FILE__).'/assets/css/main.css', false, '1.0.0');
	wp_enqueue_style('main_css');
}

add_action('admin_enqueue_scripts', 'load_custom_plugin_scripts');
add_action('admin_enqueue_scripts', 'load_custom_plugin_scripts2');
add_action('wp_enqueue_scripts', 'add_ajax_file');

add_action('wp_ajax_colorQuiz_ajax_function', 'colorQuiz_ajax_function');
add_action('wp_ajax_nopriv_colorQuiz_ajax_function', 'colorQuiz_ajax_function');


function colorQuiz_ajax_function(){
	global $current_user;
	global $wpdb;
	$user_answers = $wpdb->prefix . 'user_answers';


	if (is_user_logged_in()) {
		get_currentuserinfo();
		$user_id = $current_user->ID;
	} else {
		$user_id = $_SERVER['REMOTE_ADDR'];
	}

	 
	$q_ans = [];
	$q_ans = json_decode($_POST['q_ans']);
	$quiz_no = $_POST['quiz_no'];
	$result = $_POST['result'];

	$escaped_values = esc_sql($q_ans[0]);
	$q_ans0  = implode(", ", $escaped_values);

	$escaped_values =  esc_sql($q_ans[1]);
	$q_ans1  = implode(", ", $escaped_values);


$sql_user_answers = "INSERT INTO $user_answers (`user_id`, `quiz_id`, `questions`, `answers`, `quiz_result`) VALUES ('$user_id',$quiz_no,'$q_ans0','$q_ans1','$result')"; 
	 $wpdb->query($sql_user_answers);

	 // echo $user_id;

  //  $wpdb->show_errors(); 
  //  $wpdb->print_error();
     wp_die();
}

?>