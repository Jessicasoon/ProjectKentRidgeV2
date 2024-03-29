//EDITED BY YL ON 24AUG, CHANGED ALL createQuestion/Option/ResultObject.php to createQuestion/Option/ResultObject-test.php

// Create Quiz

// Quiz Validation Class
var QuizValidate = {
	// storage counters
	sprytextfield: new Array(),
	sprytextarea: new Array(),
	
	// init all validation objects
	init: function(){		
		// we scan through the page and identify all validation widgets
		textfield = this.sprytextfield;
		textarea = this.sprytextarea;
		$(".sprytextfield").each(function(i){ // textfields
			textfield[i] = new Spry.Widget.ValidationTextField($(this).attr('id'), "none", {validateOn:["change"]});
		});
		$(".sprytextarea").each(function(i){ // textareas
			textarea[i] = new Spry.Widget.ValidationTextarea($(this).attr('id'), {validateOn:["change"]});
			
		});
	},
	
	add: function(type, field_id){
		id = this.sprytextfield.length+1;
		if(type == "textfield"){
			this.sprytextfield[id] = new Spry.Widget.ValidationTextField($("#sprytextfield-"+field_id).attr('id'), "none", {validateOn:["change"]});
		}else{
			this.sprytextarea[id] = new Spry.Widget.ValidationTextarea($("#sprytextarea-"+field_id).attr('id'), {validateOn:["change"]});
		}
		return true;
	},
		
	remove: function(type, id){
		if(type == "textfield"){
			remover = new Spry.Widget.Utils.destroyWidgets("sprytextfield-"+id);
			return true;
		}else{
			remover = new Spry.Widget.Utils.destroyWidgets("sprytextarea-"+id);
			return true;
		}
	},
	
		/****************************************************
	 * Modify on 14 Oct for checking test result range
	 ****************************************************/
	 checkRange: function() {
		try{
			var results = document.getElementById('resultCount');
			for(var i = 0; i < results.value; i++){
				var range = document.getElementById('result_minimum_'+i);
				if(range.value == "select"){
					return false;
				}
			}
			return true;
		} catch(err){ // for step 3, var results = document.getElementById('resultCount') does not work. This will help to prevent the exception
			return true;
		}
	 }, // end checkTestResultRange function
	 
	checkTest: function(question, totalOption) {
		var notCorrect = 0;
		var optionArray = totalOption.split("_");
		for (var i = 0; i<question; i++)
		{
			notCorrect = 0;
			for (var x = 0; x<optionArray[i]; x++)
			{
				var node_list = document.getElementsByTagName('input');
				for (var j =0; j<node_list.length; j++){
					var node = node_list[j];
					if ( (node.getAttribute('name') == 'q'+i+'r'+x) && (node.getAttribute('type') == 'checkbox') ) {
						if (!(node.checked)) {
							notCorrect++;
						}		
					} 
				} 
			if (notCorrect == (optionArray[i])) {  return false; }
			} 
		} 
		return true;	
	 }//end checkTest function
	
	
}

// Quiz Information class
var QuizInfo = {
	id: 0,
	key: '',
	// store the id
	init: function(id, key){
		this.id = id;
		this.key = key;
	}
}

// Quiz Result class
var QuizResultTest = {
	resultCount: 0,
	visualCount: 0,
	
	init: function(){
		// populate the quiz
		$.ajax({
			type: "GET",
			url: "../modules/createResultObject-test.php?load",
			data: "resultNumber="+this.resultCount+"&unikey="+QuizInfo.key+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				if(data == ""){
					// show a tip
					$("#resultTip").show();
				}else{
					$("#createResultContainer").append(data);
				}
			}
		});
		// count the number of results
		numResult = 0;
		visualCount = 0;
		$(".resultWidget").each(function(i){
			numResult++;
			visualCount++;
			QuizValidate.add("textfield", "result_title_"+i);	// result title
			QuizValidate.add("textarea", "result_description_"+i);	// result description
		});
		this.resultCount = numResult;
		// update the count
		this.updateCount();
		this.visualCount = visualCount;
		// init the images
		scanInitUploader();
		
	},
	
/*
	slider: function() { // slider that works, with 2 handles
		$(".slider").each(function() {
			// $this is a reference to .slider in current iteration of each
			$this = $(this);
			 $lower = $(this).parent().find(".lowerbound").val();
			// find any .slider-range element WITHIN scope of $this
			$(".slider-range", $this).slider({
				range: true,
				min: 0,
				max: 100,
				values: [ $lower, 100 ],
				slide: function( event, ui ) {
				   // find any element with class .amount WITHIN scope of $this
				   $(this).parent().find(".amount").html( ui.values[ 0 ] + "% - " + ui.values[ 1 ] + "% " );
				  
				}
	*/			
				/*  
				//might help in getting value for next result starting % - LIEN
				//rmb to put comma above if using these functions
				start: function(event, ui) {
					start = ui.value;
					$("#start").text(start);
				},
				
				stop: function(event, ui) {
					$("#delta").text(ui.value > start ? "increasing" : "decreasing");
				}
				*/ // end of might-help
	/*	
			});
			$(".amount").html( $(".slider-range").slider("values", 0 ) + "% - " + $(".slider-range").slider("values", 1 ) + "% " );
	});
}, */


	slider: function() { // slider that works, with 1 handle.
		$(".slider").each(function() {
			// $this is a reference to .slider in current iteration of each
			$this = $(this);
			//alert( $("#resultCount").val() );
			 $lower = $(this).parent().find(".lowerbound").val();
			// find any .slider-range element WITHIN scope of $this
			$(".slider-range", $this).slider({
				range: "max",
				min: 0,
				max: 100,
				values: 75,
				slide: function( event, ui ) {
				   // find any element with class .amount WITHIN scope of $this

				   $(this).parent().find(".amount").html( ui.value + "% ");

				}
			});
			$(".amount"+ $("#resultCount").val()).html( $(".slider-range").slider("values", 0 ) + "% " );
	});
}, 



	/*slider: function() { //http://jqueryui.com/demos/slider/#range --using this logic for above - LIEN
		$( "#slider-range").slider({
			range: true,
			min: 0,
			max: 500,
			values: [75, 300],
			slide: function( event, ui ) {
				$( "#amount").val( "$" + ui.values[0] + " - $" + ui.values[1] );
			}
		});
		$( "#amount").val( "$" + $( "#slider-range").slider( "values", 0 ) + " - $" + $( "#slider-range").slider( "values", 1 ) );
	},
	*/
	
/*	slider: function() { //http://jqueryui.com/demos/slider/#rangemax
		$( "#slider-range-"+this.resultCount).slider({
			range: "max",
			min: 0,
			max: 100,
			value: 75,
			slide: function( event, ui ) {
				$( "#amount-"+this.resultCount).val("100% - " + ui.value + "%"); // ui.value changes according to value user slides the bar by
			}
		});
		$( "#amount-"+this.resultCount).val("100% - " + $( "#slider-range-"+this.resultCount).slider( "value" ) + "%"); // initial value stated in text box
	},
	*/
	
	add: function(){
		// add the result widget
		$.ajax({
			type: "GET",
			url: "../modules/createResultObject-test.php",
			data: "resultNumber="+this.resultCount+"&unikey="+QuizInfo.key,
			async: false,
			success: function(data) {
				$("#createResultContainer").append(data);
			}
		});
		QuizValidate.add("textfield", "result_title_"+this.resultCount);	// result title
		QuizValidate.add("textarea", "result_description_"+this.resultCount);	// result description
		// init the picture uploader
		initUploader(this.resultCount);

		// return the value and increment
		this.resultCount++;
		this.visualCount++;
		if(this.visualCount > 0){
			$("#resultTip").hide();
		}
		// update the count
		this.updateCount();
		return this.resultCount;
	},
	
	remove: function(id){
		if(confirm("Are you sure you want to remove this result? This action cannot be undone!")){
			// unregister the validators
			QuizValidate.remove("textfield", "result_title_"+id);		// remove title
			QuizValidate.remove("textarea", "result_description_"+id);	// remove description
			// is it already in the database ?
			if($("#ur"+id).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					url: "../modules/createResultObject-test.php?delete",
					data: "result="+$("#ur"+id).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// just remove the question widget
			$("#r"+id).remove();
			this.visualCount--;
			// update the count
			this.updateCount();
			
			if(this.visualCount == 0){
				$("#resultTip").show();
			}
			return this.resultCount
		}else{
			return false;
		}
	},
	
	updateCount: function(){
		$("#resultCount").val(this.resultCount);
	}
}

// Quiz Result class
var QuizResultMulti = {
	resultCount: 0,
	visualCount: 0,
	
	init: function(){
		// populate the quiz
		$.ajax({
			type: "GET",
			url: "../modules/createResultObject-multi.php?load",
			data: "resultNumber="+this.resultCount+"&unikey="+QuizInfo.key+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				if(data == ""){
					// show a tip
					$("#resultTip").show();
				}else{
					$("#createResultContainer").append(data);
				}
			}
		});
		// count the number of results
		numResult = 0;
		visualCount = 0;
		$(".resultWidget").each(function(i){
			numResult++;
			visualCount++;
			QuizValidate.add("textfield", "result_title_"+i);	// result title
			QuizValidate.add("textarea", "result_description_"+i);	// result description
		});
		this.resultCount = numResult;
		// update the count
		this.updateCount();
		this.visualCount = visualCount;
		// init the images
		scanInitUploader();
		
	},
	
	add: function(){
		// add the result widget
		$.ajax({
			type: "GET",
			url: "../modules/createResultObject-multi.php",
			data: "resultNumber="+this.resultCount+"&unikey="+QuizInfo.key,
			async: false,
			success: function(data) {
				$("#createResultContainer").append(data);
			}
		});
		QuizValidate.add("textfield", "result_title_"+this.resultCount);	// result title
		QuizValidate.add("textarea", "result_description_"+this.resultCount);	// result description
		// init the picture uploader
		initUploader(this.resultCount);

		// return the value and increment
		this.resultCount++;
		this.visualCount++;
		if(this.visualCount > 0){
			$("#resultTip").hide();
		}
		// update the count
		this.updateCount();
		return this.resultCount;
	},
	
	remove: function(id){
		if(confirm("Are you sure you want to remove this result? This action cannot be undone!")){
			// unregister the validators
			QuizValidate.remove("textfield", "result_title_"+id);		// remove title
			QuizValidate.remove("textarea", "result_description_"+id);	// remove description
			// is it already in the database ?
			if($("#ur"+id).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					url: "../modules/createResultObject-multi.php?delete",
					data: "result="+$("#ur"+id).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// just remove the question widget
			$("#r"+id).remove();
			this.visualCount--;
			// update the count
			this.updateCount();
			
			if(this.visualCount == 0){
				$("#resultTip").show();
			}
			return this.resultCount
		}else{
			return false;
		}
	},
	
	updateCount: function(){
		$("#resultCount").val(this.resultCount);
	}
}

// Quiz Result class
var QuizResult_NOTUSED = {
	resultCount: 0,
	visualCount: 0,
	
	init: function(){
		// populate the quiz
		$.ajax({
			type: "GET",
			url: "../modules/createResultObject.php?load",
			data: "resultNumber="+this.resultCount+"&unikey="+QuizInfo.key+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				if(data == ""){
					// show a tip
					$("#resultTip").show();
				}else{
					$("#createResultContainer").append(data);
				}
			}
		});
		// count the number of results
		numResult = 0;
		visualCount = 0;
		$(".resultWidget").each(function(i){
			numResult++;
			visualCount++;
			QuizValidate.add("textfield", "result_title_"+i);	// result title
			QuizValidate.add("textarea", "result_description_"+i);	// result description
		});
		this.resultCount = numResult;
		// update the count
		this.updateCount();
		this.visualCount = visualCount;
		// init the images
		scanInitUploader();
		
	},
	
	add: function(){
		// add the result widget
		$.ajax({
			type: "GET",
			url: "../modules/createResultObject.php",
			data: "resultNumber="+this.resultCount+"&unikey="+QuizInfo.key,
			async: false,
			success: function(data) {
				$("#createResultContainer").append(data);
			}
		});
		QuizValidate.add("textfield", "result_title_"+this.resultCount);	// result title
		QuizValidate.add("textarea", "result_description_"+this.resultCount);	// result description
		// init the picture uploader
		initUploader(this.resultCount);

		// return the value and increment
		this.resultCount++;
		this.visualCount++;
		if(this.visualCount > 0){
			$("#resultTip").hide();
		}
		// update the count
		this.updateCount();
		return this.resultCount;
	},
	
	remove: function(id){
		if(confirm("Are you sure you want to remove this result? This action cannot be undone!")){
			// unregister the validators
			QuizValidate.remove("textfield", "result_title_"+id);		// remove title
			QuizValidate.remove("textarea", "result_description_"+id);	// remove description
			// is it already in the database ?
			if($("#ur"+id).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					url: "../modules/createResultObject-test.php?delete",
					data: "result="+$("#ur"+id).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// just remove the question widget
			$("#r"+id).remove();
			this.visualCount--;
			// update the count
			this.updateCount();
			
			if(this.visualCount == 0){
				$("#resultTip").show();
			}
			return this.resultCount
		}else{
			return false;
		}
	},
	
	updateCount: function(){
		$("#resultCount").val(this.resultCount);
	}
}
var QuizQuestionTest = {
	question: new Array(),
	visualCount: 0,
	
	init: function(){
		// populate the questions
		$.ajax({
			type: "GET",
			//url: "../modules/createQuestionObject.php?load",
			url: "../modules/createQuestionObject-test.php?load",
			data: "questionNumber="+this.numQuestions()+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				if(data == ""){
					$("#questionTip").show();
				}else{
					$("#createQuestionContainer").append(data);
				}
			}
		});		
		thisQuestion = this.question;
		visualCount = 0;
		// count the number of questions
		$(".questionWidget").each(function(i){
			thisQuestion[i] = new Array();
			QuizValidate.add("textfield", 'q'+i); // question
			visualCount++;
			// find out the number of options in a question
			$(".optionWidget-"+i).each(function(j){
				thisQuestion[i][j] = 'q'+i+'o'+j;
				QuizValidate.add("textfield", 'q'+i+'o'+j); // option
			});
		});
		this.visualCount = visualCount;
		this.updateCount();
		this.getOptionValues();
	},
	
	add: function(){
		// add the question widget
		$.ajax({
			type: "GET",
			//url: "../modules/createQuestionObject.php",
			url: "../modules/createQuestionObject-test.php",
			data: "questionNumber="+this.numQuestions()+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				$("#createQuestionContainer").append(data);
			}
		});
		// init the validators
		QuizValidate.add("textfield", 'q'+this.question.length); // question
		QuizValidate.add("textfield", 'q'+this.question.length+'o0'); // option 1
		QuizValidate.add("textfield", 'q'+this.question.length+'o1'); // option 2
		// update the array counts		
		this.question[this.question.length] = new Array();
		this.question[this.question.length-1][0] = 'q'+this.question.length+'o0';
		this.question[this.question.length-1][1] = 'q'+this.question.length+'o1';
		// update the page counts
		this.updateCount();
		this.visualCount++;
		if(this.visualCount > 0){
			$("#questionTip").hide();
		}
		this.getOptionValues();
		return this.question.length;
	},
	
	addOption: function(question_id){
		nextOption = this.question[question_id].length;
		// add an option widget
		$.ajax({
			type: "GET",
			//url: "../modules/createOptionObject.php",
			url: "../modules/createOptionObject-test.php",
			data: "questionNumber="+question_id+"&optionNumber="+nextOption+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				$("#optionContainer_"+question_id).append(data);
			}
		});
		// add an option
		this.question[question_id][nextOption] = 'q'+question_id+'o'+nextOption;
		QuizValidate.add("textfield", 'q'+question_id+'o'+nextOption); // one option
		return this.question[question_id].length;
	},
	
	remove: function(id){
		if(confirm("Are you sure you want to remove this question and its options? This action cannot be undone!")){
			// find and remove the options in it
			for(i=0; i < this.question[id].length; i++){
				if(this.question[id][i] != undefined){
					this.removeOption(id, i, true);
				}
			}
			// unregister the question validator
			QuizValidate.remove("textfield", "q"+id);
			// is it already in the database ?
			if($("#uq"+id).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					//url: "../modules/createQuestionObject.php?delete",
					url: "../modules/createQuestionObject-test.php?delete",
					data: "question="+$("#uq"+id).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the question widget
			$("#q"+id).remove();
			delete this.question[id];
			this.updateCount();
			this.getOptionValues();
			this.visualCount--;
			if(this.visualCount == 0){
				$("#questionTip").show();
			}
			return true;
		}else{
			return false;
		}
	},
	
	removeOption: function(question, option, mute){
		if(mute){
			ask = true;
		}else{
			ask = confirm("Are you sure you want to remove this option? This action cannot be undone!");
		}
		if(ask){
			// unregister the validators
			QuizValidate.remove("textfield", 'q'+question+'o'+option);
			// is it already in the database ?
			if($('#uq'+question+'o'+option).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					//url: "../modules/createOptionObject.php?delete",
					url: "../modules/createOptionObject-test.php?delete",
					data: "option="+$('#uq'+question+'o'+option).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the option widget
			$('#cq'+question+'o'+option).remove();
			delete this.question[question][option];
			return true;
		}else{
			return false;
		}
	},
	
	numQuestions: function(){
		return this.question.length;
	},
	
	getOptionValues: function(){
		var textString = "";

		for(i = 0; i < this.question.length; i++){
			if(this.question[i] != undefined){
				textString += this.question[i].length + '_';
			}else{
				textString += 0 + '_';
			}
		}
		returnVal = textString.substr(0, textString.length-1);
		$("#optionCounts").val(returnVal);
		return returnVal;
	},

	updateCount: function(){
		$("#questionCount").val(this.question.length);
	}
}
var QuizQuestionMulti = {
	question: new Array(),
	visualCount: 0,
	
	init: function(){
		// populate the questions
		$.ajax({
			type: "GET",
			//url: "../modules/createQuestionObject.php?load",
			url: "../modules/createQuestionObject-multi.php?load",
			data: "questionNumber="+this.numQuestions()+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				if(data == ""){
					$("#questionTip").show();
				}else{
					$("#createQuestionContainer").append(data);
				}
			}
		});		
		thisQuestion = this.question;
		visualCount = 0;
		// count the number of questions
		$(".questionWidget").each(function(i){
			thisQuestion[i] = new Array();
			QuizValidate.add("textfield", 'q'+i); // question
			visualCount++;
			// find out the number of options in a question
			$(".optionWidget-"+i).each(function(j){
				thisQuestion[i][j] = 'q'+i+'o'+j;
				QuizValidate.add("textfield", 'q'+i+'o'+j); // option
			});
		});
		this.visualCount = visualCount;
		this.updateCount();
		this.getOptionValues();
	},
	
	add: function(){
		// add the question widget
		$.ajax({
			type: "GET",
			//url: "../modules/createQuestionObject.php",
			url: "../modules/createQuestionObject-multi.php",
			data: "questionNumber="+this.numQuestions()+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				$("#createQuestionContainer").append(data);
			}
		});
		// init the validators
		QuizValidate.add("textfield", 'q'+this.question.length); // question
		QuizValidate.add("textfield", 'q'+this.question.length+'o0'); // option 1
		QuizValidate.add("textfield", 'q'+this.question.length+'o1'); // option 2
		// update the array counts		
		this.question[this.question.length] = new Array();
		this.question[this.question.length-1][0] = 'q'+this.question.length+'o0';
		this.question[this.question.length-1][1] = 'q'+this.question.length+'o1';
		// update the page counts
		this.updateCount();
		this.visualCount++;
		if(this.visualCount > 0){
			$("#questionTip").hide();
		}
		this.getOptionValues();
		return this.question.length;
	},
	
	addOption: function(question_num, question_id){
		nextOption = this.question[question_id].length;
		// add an option widget
		$.ajax({
			type: "GET",
			//url: "../modules/createOptionObject.php",
			url: "../modules/createOptionObject-multi.php",
			data: "questionNumber="+question_id+"&optionNumber="+nextOption+"&id="+QuizInfo.id+"&questionID="+question_num,
			async: false,
			success: function(data) {
				$("#optionContainer_"+question_id).append(data);
			}
		});
		// add an option
		this.question[question_id][nextOption] = 'q'+question_id+'o'+nextOption;
		QuizValidate.add("textfield", 'q'+question_id+'o'+nextOption); // one option
		return this.question[question_id].length;
	},
	
	addOptionNew: function(question_id){
		nextOption = this.question[question_id].length;
		// add an option widget
		$.ajax({
			type: "GET",
			//url: "../modules/createOptionObject.php",
			url: "../modules/createOptionObject-multi.php",
			data: "questionNumber="+question_id+"&optionNumber="+nextOption+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				$("#optionContainer_"+question_id).append(data);
			}
		});
		// add an option
		this.question[question_id][nextOption] = 'q'+question_id+'o'+nextOption;
		QuizValidate.add("textfield", 'q'+question_id+'o'+nextOption); // one option
		return this.question[question_id].length;
	},	
	
	remove: function(id){
		if(confirm("Are you sure you want to remove this question and its option? This action cannot be undone!")){
			// find and remove the options in it
			for(i=0; i < this.question[id].length; i++){
				if(this.question[id][i] != undefined){
					this.removeOptionNew(id,  i, true);
				}
			}
			// unregister the question validator
			QuizValidate.remove("textfield", "q"+id);
			// is it already in the database ?
			if($("#uq"+id).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					//url: "../modules/createQuestionObject.php?delete",
					url: "../modules/createQuestionObject-multi.php?delete",
					data: "question="+$("#uq"+id).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the question widget
			$("#q"+id).remove();
			delete this.question[id];
			this.updateCount();
			this.getOptionValues();
			this.visualCount--;
			if(this.visualCount == 0){
				$("#questionTip").show();
			}
			return true;
		}else{
			return false;
		}
	},
	
	
	
	removeOption: function(question_id, question, option, mute){
		if(mute){
			ask = true;
		}else{
			ask = confirm("Are you sure you want to remove this option? This action cannot be undone!");
		}
		if(ask){
			// unregister the validators
			QuizValidate.remove("textfield", 'q'+question+'o'+option);
			// is it already in the database ?
			if($('#uq'+question+'o'+option).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET", 
					//url: "../modules/createOptionObject.php?delete",
					url: "../modules/createOptionObject-multi.php?delete",
					data: "option="+$('#uq'+question+'o'+option).val()+"&id="+QuizInfo.id+"&questionNumber="+question_id,  //add to get question id by YL 15oct
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the option widget
			$('#cq'+question+'o'+option).remove();
			delete this.question[question][option];
			return true;
		}else{
			return false;
		}
	},

	removeOptionNew: function(question, option, mute){
		if(mute){
			ask = true;
		}else{
			ask = confirm("Are you sure you want to remove this option? This action cannot be undone!");
		}
		if(ask){
			// unregister the validators
			QuizValidate.remove("textfield", 'q'+question+'o'+option);
			// is it already in the database ?
			if($('#uq'+question+'o'+option).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET", 
					//url: "../modules/createOptionObject.php?delete",
					url: "../modules/createOptionObject-multi.php?delete",
					data: "option="+$('#uq'+question+'o'+option).val()+"&id="+QuizInfo.id,  //add to get question id by YL 15oct
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the option widget
			$('#cq'+question+'o'+option).remove();
			delete this.question[question][option];
			return true;
		}else{
			return false;
		}
	},
	
	numQuestions: function(){
		return this.question.length;
	},
	
	getOptionValues: function(){
		var textString = "";

		for(i = 0; i < this.question.length; i++){
			if(this.question[i] != undefined){
				textString += this.question[i].length + '_';
			}else{
				textString += 0 + '_';
			}
		}
		returnVal = textString.substr(0, textString.length-1);
		$("#optionCounts").val(returnVal);
		return returnVal;
	},

	updateCount: function(){
		$("#questionCount").val(this.question.length);
	}
}
var QuizQuestion_NOTUSED = {
	question: new Array(),
	visualCount: 0,
	
	init: function(){
		// populate the questions
		$.ajax({
			type: "GET",
			//url: "../modules/createQuestionObject.php?load",
			url: "../modules/createQuestionObject-test.php?load",
			data: "questionNumber="+this.numQuestions()+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				if(data == ""){
					$("#questionTip").show();
				}else{
					$("#createQuestionContainer").append(data);
				}
			}
		});		
		thisQuestion = this.question;
		visualCount = 0;
		// count the number of questions
		$(".questionWidget").each(function(i){
			thisQuestion[i] = new Array();
			QuizValidate.add("textfield", 'q'+i); // question
			visualCount++;
			// find out the number of options in a question
			$(".optionWidget-"+i).each(function(j){
				thisQuestion[i][j] = 'q'+i+'o'+j;
				QuizValidate.add("textfield", 'q'+i+'o'+j); // option
			});
		});
		this.visualCount = visualCount;
		this.updateCount();
		this.getOptionValues();
	},
	
	add: function(){
		// add the question widget
		$.ajax({
			type: "GET",
			//url: "../modules/createQuestionObject.php",
			url: "../modules/createQuestionObject-test.php",
			data: "questionNumber="+this.numQuestions()+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				$("#createQuestionContainer").append(data);
			}
		});
		// init the validators
		QuizValidate.add("textfield", 'q'+this.question.length); // question
		QuizValidate.add("textfield", 'q'+this.question.length+'o0'); // option 1
		QuizValidate.add("textfield", 'q'+this.question.length+'o1'); // option 2
		// update the array counts		
		this.question[this.question.length] = new Array();
		this.question[this.question.length-1][0] = 'q'+this.question.length+'o0';
		this.question[this.question.length-1][1] = 'q'+this.question.length+'o1';
		// update the page counts
		this.updateCount();
		this.visualCount++;
		if(this.visualCount > 0){
			$("#questionTip").hide();
		}
		this.getOptionValues();
		return this.question.length;
	},
	
	addOption: function(question_id){
		nextOption = this.question[question_id].length;
		// add an option widget
		$.ajax({
			type: "GET",
			//url: "../modules/createOptionObject.php",
			url: "../modules/createOptionObject-test.php",
			data: "questionNumber="+question_id+"&optionNumber="+nextOption+"&id="+QuizInfo.id,
			async: false,
			success: function(data) {
				$("#optionContainer_"+question_id).append(data);
			}
		});
		// add an option
		this.question[question_id][nextOption] = 'q'+question_id+'o'+nextOption;
		QuizValidate.add("textfield", 'q'+question_id+'o'+nextOption); // one option
		return this.question[question_id].length;
	},
	
	remove: function(id){
		if(confirm("Are you sure you want to remove this question and its options? This action cannot be undone!")){
			// find and remove the options in it
			for(i=0; i < this.question[id].length; i++){
				if(this.question[id][i] != undefined){
					this.removeOption(id, i, true);
				}
			}
			// unregister the question validator
			QuizValidate.remove("textfield", "q"+id);
			// is it already in the database ?
			if($("#uq"+id).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					//url: "../modules/createQuestionObject.php?delete",
					url: "../modules/createQuestionObject-test.php?delete",
					data: "question="+$("#uq"+id).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the question widget
			$("#q"+id).remove();
			delete this.question[id];
			this.updateCount();
			this.getOptionValues();
			this.visualCount--;
			if(this.visualCount == 0){
				$("#questionTip").show();
			}
			return true;
		}else{
			return false;
		}
	},
	
	removeOption: function(question, option, mute){
		if(mute){
			ask = true;
		}else{
			ask = confirm("Are you sure you want to remove this option? This action cannot be undone!");
		}
		if(ask){
			// unregister the validators
			QuizValidate.remove("textfield", 'q'+question+'o'+option);
			// is it already in the database ?
			if($('#uq'+question+'o'+option).val() != undefined){
				// remove from database
				$.ajax({
					type: "GET",
					//url: "../modules/createOptionObject.php?delete",
					url: "../modules/createOptionObject-test.php?delete",
					data: "option="+$('#uq'+question+'o'+option).val()+"&id="+QuizInfo.id,
					async: false,
					success: function(data) {
						if(data != ""){
							alert(data);
						}
					}
				});
			}
			// remove the option widget
			$('#cq'+question+'o'+option).remove();
			delete this.question[question][option];
			return true;
		}else{
			return false;
		}
	},
	
	numQuestions: function(){
		return this.question.length;
	},
	
	getOptionValues: function(){
		var textString = "";

		for(i = 0; i < this.question.length; i++){
			if(this.question[i] != undefined){
				textString += this.question[i].length + '_';
			}else{
				textString += 0 + '_';
			}
		}
		returnVal = textString.substr(0, textString.length-1);
		$("#optionCounts").val(returnVal);
		return returnVal;
	},

	updateCount: function(){
		$("#questionCount").val(this.question.length);
	}
}

function submitCheckTest(value){
	// check if upload complete
	if(!QuizValidate.checkRange()){
			alert("Please choose values for result range");
			return false;
	}
	if (!QuizValidate.checkTest($('#questionCount').val(),$("#optionCounts").val() )) // check that all questions have at least 1 ticked option
		{
			alert("Please tick at least one option for all questions");
			return false;
	}
	if(value && !checkIfUploading()){
		$('#submitBtn').attr("disabled", "disabled");
		$('#resultCount').val(QuizResultTest.resultCount);
		$('#questionCount').val(QuizQuestionTest.numQuestions());
		$("#optionCounts").val(QuizQuestionTest.getOptionValues());
		return true;
	}else{
		if(checkIfUploading()){
			alert("Photo uploads still in progress! Please wait for uploads to complete!");
		}
		if(!value){
			alert("Some of the required fields are empty! Please scroll up to check. Fields requiring attention will be highlighted red.");	
		}
		return false;
	}
	
}

function submitCheckMulti(value){
	// check if upload complete
	if(value && !checkIfUploading()){
		$('#submitBtn').attr("disabled", "disabled");
		$('#resultCount').val(QuizResultMulti.resultCount);
		$('#questionCount').val(QuizQuestionMulti.numQuestions());
		$("#optionCounts").val(QuizQuestionMulti.getOptionValues());
		return true;
	}else{
		if(checkIfUploading()){
			alert("Photo uploads still in progress! Please wait for uploads to complete!");
		}
		if(!value){
			alert("Some of the required fields are empty! Please scroll up to check. Fields requiring attention will be highlighted red.");	
		}
		return false;
	}
}

function submitCheck(value){
	// check if upload complete
	if(value && !checkIfUploading()){
		$('#submitBtn').attr("disabled", "disabled");
		$('#resultCount').val(QuizResultTest.resultCount);
		$('#questionCount').val(QuizQuestionTest.numQuestions());
		$("#optionCounts").val(QuizQuestionTest.getOptionValues());
		return true;
	}else{
		// Modified on 24 Oct: adding try catch because not all form has uploading function, without try catch will return exception
		try{
			if(checkIfUploading()){
				alert("Photo uploads still in progress! Please wait for uploads to complete!");
			}
		} catch(err){}
		if(!value){
			alert("Some of the required fields are empty! Please scroll up to check. Fields requiring attention will be highlighted red.");	
		}
		return false;
	}
}

	
