<style>
.dark {
	color: black;
}
</style>
<div class="container-fluid addEdit">
	<h1>User's Submited Quizes</h1>

 <br>


<?php
// globaly used variables
	global $wpdb;
	$user_answers = $wpdb->prefix . 'user_answers';
	$quizes = $wpdb->prefix . 'quizes';
	$questions = $wpdb->prefix . 'questions';
	$answers = $wpdb->prefix . 'answers';
	$results_table = $wpdb->prefix . 'results';
?>



<?php 
// Delete User Data
if(isset($_GET['delete_record']) && !empty($_GET['delete_record'])){
	$delete_record = $_GET['delete_record'];
	$wpdb->query("DELETE FROM $user_answers WHERE user_id = '$delete_record'");
}

 ?>


<?php
$css; 
if(isset($_GET['hide_users'])){
	$css = 'none';
}
 ?>
 <div style="display: <?= $css; ?>">
	<table class="table table-bordered table-hover"  >
		<thead>
			<tr>
				<th>User ID</th>
				<th>User Name</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php

$sql_user_answers = "SELECT DISTINCT user_id FROM $user_answers";

// $sql_user_answers = "SELECT  DISTINCT user_id, count('user_id') as total_ids FROM $user_answers";
$results = $wpdb->get_results($sql_user_answers);

if(!empty($results)){

	foreach( $results as $result ) {

$user = get_user_by( 'id', $result->user_id );

if($result->total_ids !== '0' ) {
 ?>
			<tr>
				<td><?= $result->user_id ?></td>
				<td><?= $user->data->user_nicename ?></td>
				<td>
				<a class="btn btn-primary" href="<?= admin_url(); ?>admin.php?page=colorQuiz-users&hide_users&u_id=<?= $result->user_id; ?>">See Quizes</a>
				<a class="btn btn-danger" href="<?= admin_url(); ?>admin.php?page=colorQuiz-users&delete_record=<?= $result->user_id; ?>">Delete Record</a>
				</td>
			</tr>

<?php 
}else{
	?>

			<tr>
				<td colspan="4"><center>No Data Found</center></td>
			</tr>
	<?php
}
}
}else {
?>
			<tr>
				<td colspan="4"><center>No Data Found</center></td>
			</tr>
<?php 

}
?>
		</tbody>
	</table>


</div>













<?php 
if(isset($_GET['u_id']) && !empty($_GET['u_id'])){
	$u_id = $_GET['u_id'];
?>

 <div>
 <?php 
	$user = get_user_by( 'id', $u_id );
  ?>
  <h2><span class="dark">Quiz Submited By</span> <?= $user->data->user_nicename ?> </h2>
  	<table class="table table-bordered table-hover"  >
		<thead>
			<tr>
				<th>Date&Time</th>
				<th>Quiz Name</th>
				<th>Quiz Title</th>
				<th>Total Quiz</th>
				<th>Result</th>
				<th>Offer</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php

$sql_users_answers = "
SELECT count(user_id) as user_count , ua.created as u_created,  ua.*, q.* 
FROM $user_answers ua 
JOIN $quizes as q 
on ua.quiz_id = q.id 

 
where user_id = $u_id
GROUP BY quiz_id
";

$resultss = $wpdb->get_results($sql_users_answers);
// echo "<pre>";
// print_r($resultss);
// echo "</pre>";

if(!empty($resultss)){

	foreach( $resultss as $result ) {
if($result->total_ids !== '0' ) {
 ?>
			<tr>
				<td><?= date_format(date_create($result->u_created),"M-d-Y H:i:s:a");?></td>
				<td><?= $result->name; ?></td>
 				<td><?= $result->title; ?></td>
 				<td><?= $result->user_count; ?></td>
 				<td><?= $result->your_type; ?></td>
 				<td><?= $result->offer; ?></td>
				<td>
				<a class="btn btn-primary" href="<?= admin_url(); ?>admin.php?page=colorQuiz-users&hide_users&q_id=<?= $result->quiz_id; ?>&user_id=<?= $result->user_id; ?>">Detail</a>
			</tr>

<?php 
}else{
	?>

			<tr>
				<td colspan="4"><center>No Data Found</center></td>
			</tr>
	<?php
}
}
}else {
?>
			<tr>
				<td colspan="4"><center>No Data Found</center></td>
			</tr>
<?php 

}
?>
		</tbody>
	</table>


</div>



<?php
}
?>















<?php 


//  Get User Data

if(isset($_GET['user_id']) && !empty($_GET['user_id']) && !empty($_GET['q_id'])){
 $user_id = $_GET['user_id'];
 $quiz_id = $_GET['q_id'];
 ?>
 <h2><span class="dark">Quiz Submited by:</span> <?php 
	$user = get_user_by( 'id', $user_id);
	echo $user->data->user_nicename ;
  ?></h2>

<hr>

<?php

$sql_user_answers = "SELECT  ua.*, r.quiz_id as r_quiz_id, r.your_type, r.offer
FROM $user_answers ua

JOIN $results_table as r
on ua.quiz_result = r.result_id and ua.quiz_id = r.quiz_id

where ua.user_id = $user_id AND ua.quiz_id = $quiz_id";

$results = $wpdb->get_results($sql_user_answers);

if(!empty($results)){
	foreach( $results as $result ) {


$questions_num = $result->questions;
$questionsArray = explode(',', $questions_num);
$total_questions = count($questionsArray);
// print_r($questionsArray);
$answers_num = $result->answers;
$answersArray = explode(',', $answers_num);
$total_answers = count($answersArray);
// print_r($answersArray);

$sql_quizes = "SELECT * FROM $quizes where id = ".$result->quiz_id;
$quizes_results = $wpdb->get_results($sql_quizes);
 ?>
<h3><span class="dark">Quiz Name:</span>  <?= $quizes_results[0]->name?></h3>
<h3><span class="dark">Quiz Title:</span> <?= $quizes_results[0]->title?></h3>
<h3><span class="dark">Quiz Result:</span>  <?= $result->your_type?></h3>
<h3><span class="dark">Quiz Offer:</span> <?= $result->offer?></h3>
<br>
<?php 

for ($x = 0; $x <= $total_questions -1 ; $x++) {

	$sql_questions = "SELECT * FROM $questions where id = ".$questionsArray[$x];
	$questions_results = $wpdb->get_results($sql_questions);
	$q_n =$questions_results[0]->id;
	$sql_answers = "SELECT * FROM $answers where q_id = '$q_n'  AND id = ".$answersArray[$x];
	$answers_results = $wpdb->get_results($sql_answers);
// echo $sql_answers;
		echo '<h5 class="qs"><span>Q:</span> ' . $questions_results[0]->question . '</h5>';
		echo '<p class="ans"><span class="dark">Ans: </span>' . $answers_results[0]->answer . '</p><br>';

   // $wpdb->show_errors(); 
   // $wpdb->print_error();
} 

 ?>

<?php 
?>
	

<?php 
}
}else {
?>
		<tr>
			<td colspan="4"><center>No Data Found</center></td>
		</tr>
<?php 

}
}
?>
</div>