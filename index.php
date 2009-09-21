<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <style>   
		#wordContainer
		{
			position: relative;
			height: 200px;
			border-bottom: 2px dashed #888;
		}
		#word
		{
			position: absolute;
			top: 0px;
			left; 0px;
			width: 100%;
			height: 200px;
			text-align: center;
			line-height: 200px;
			font-weight: bold;
			font-size: 200px;
			color: #888;
		}
		#wordDef
		{
			
		}
		#controls
		{
			position: absolute;
			bottom: 0px;
			left: 45%;
		}
		input
		{
			border: 1px solid #ccc;
		}
        </style>
        <script src="scripts/jquery-1.3.2.min.js"></script>
		<script src="http://www.google.com/jsapi" type="text/javascript"></script>
        <script>
			wordCount = 0;
			wordID = 0;
			function loadword(word)
			{
				$.getJSON("commands.php",{ cmd: "get", idx: word}, function(data){
					$("#word").html(data.word);
					$("#wordDef").html(data.definition);
					wordID = data.id;
					var searchControl = new google.search.SearchControl();

					searchControl.addSearcher(new google.search.WebSearch());
					searchControl.addSearcher(new google.search.VideoSearch());
					searchControl.addSearcher(new google.search.BlogSearch());
					searchControl.addSearcher(new google.search.NewsSearch());
					searchControl.addSearcher(new google.search.ImageSearch());
					searchControl.addSearcher(new google.search.BookSearch());

					searchControl.draw(document.getElementById("searchcontrol"));
					searchControl.execute(data.word);
				});
				$("#defContainer").hide();
			}
            $(document).ready(function(e){
				loadword(wordCount);
                $("#login").click(function(e1){
                    $.post("login.php",{ username: $("#username").val(), password: $("#password").val()}, function(data){
						$("#loginWidget").hide();
					},"json");
                });
				$("#next").click(function(){
					wordCount++;
					loadword(wordCount);
				});
				$("#prev").click(function(){
					wordCount--;
					loadword(wordCount);
				});
				$("#hit").click(function(){
					$.post("commands.php",{ cmd: "hit", id: wordID});
					wordCount++;
					loadword(wordCount);
				});
				$("#miss").click(function(){
					$.post("commands.php",{ cmd: "miss", id: wordID});
					$("#wordDef").toggle(200);
				});
				$("#word").click(function(){
					$("#defContainer").toggle(200);
				});
            });
			google.load('search', '1');
        </script>
    </head>
    <body>
        <div id="doc">
            <div id="hd">
				<div id="loginWidget">
					Email: <input id="username" name="username" type="text" />
					Password: <input id="password" name="password" type="password" />
					New User?<input id="new" name="new" type="checkbox" />
					<input id="login" type="button" value=" > " />
				</div>
            </div>
            <div id="bd">
                <div id="wordContainer">
					<div id="word">word</div>
					<div id="controls">
						<a id="prev"><-</a>
						<a id="hit">hit</a>
						<a id="miss">miss</a>
						<a id="next">-></a>
					</div>
                </div>
				<div id="defContainer">
					<div id="wordDef">definition</div>
					<div id="searchcontrol">Loading</div>
				</div>
			</div>
            <div id="ft"><p>Footer</p></div>
        </div>
    </body>
</html>