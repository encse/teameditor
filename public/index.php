<!DOCTYPE html>
<?php require('./bootstrap.php'); ?>
<html>
<head>
	<title>ISC Team Editor</title>
	<?php csv_head(); ?>

	<script src="js/jquery.js"></script>
	<script src="js/jquery.urlparams.js"></script>
	<script src="js/jquery.rotate.js"></script>

<script type="text/javascript">
	
	var stText;
	function Sign(st)
	{
		this.fLoaded = false;
		this.Toggle = function()
		{
			this.fToggle = !this.fToggle;
			if(this.fLoaded)
				this.AdjustImage();
		}
		
		this.SetText = function(st)
		{
			this.angle = 0;
			$('#sign *').remove();
			$('#sign').append(jQuery('<img />'));
			var sign = this;
			$('#sign img')
				.attr({src: 'text.php?q='+escape(st)})
				.one('load', function(){sign.fLoaded = true; sign.AdjustImage()}).each(function(){
					if(this.comlete) $(this).load();
				});
				
			stText = st;
			UpdateLink();
		}
		
		this.AdjustImage = function()
		{
		
			if (!this.fToggle)
			{
				$('#sign').css({'left':'340px', 'top':'44px'});
				$('#sign *').rotate(1-this.angle);
				this.angle=1;
			}
			else
			{
				$('#sign').css({'left':'365px', 'top':'38px'})
				$('#sign *').rotate(11-this.angle);
				this.angle=11;
			}
		}
		$('#image-container').prepend('<div id="sign"></div>');
		$('#sign').css({'left':'342px', 'top':'45px'})
		this.angle = 0; 
		this.fToggle = false;
		this.SetText(st);
	}
	
	var mask = 0;
	function Person(urlImage, x, y, height, maskId, rgpoly, sign)
	{
		this._urlImage = urlImage;
		var sign = sign;
		
		var img = jQuery('<img />').attr('src', urlImage);
		var div = jQuery('<div />').attr('class', 'person').css({'top': y+'px', 'left': x+'px', 'height': height+'px'});
		$(div).append(img);
		
		if(rgpoly)
		{
			for(var i=0;i<rgpoly.length;i++)
			{
				for(var j=0;j<rgpoly[i].length;j+=2)
				{
					rgpoly[i][j] += x;
					rgpoly[i][j+1] += y;
				}
				var area= document.createElement('area');
				$(area).attr({'shape': 'poly', 'coords': rgpoly[i]}).click(Toggle).css({'cursor':'pointer'});
				$('#themap').append(area);
			}
		}
		
		function Toggle()
		{
			mask ^= maskId;
			UpdateImage((mask & maskId) != 0);
			UpdateLink();
			if(sign)
				sign.Toggle();
		}
		if((mask & maskId) != 0)
		{	
			UpdateImage(true);
			if(sign)
				sign.Toggle();
		}
		else
		{
			UpdateImage(false);
		}
			
		$('#image-container').append(div);
		
		function UpdateImage(fToggle)
		{
			if(fToggle)
				$(img).css({top:'-'+height+'px'}); 
			else
				$(img).css({top:'-'+0+'px'}); 
		}
	}
    $(document).ready(function() {
		stInitialText = $(document).getUrlParam("q");
		if( $(document).getUrlParam("t"))
			mask = $(document).getUrlParam("t");
			
		if(stInitialText)
			stInitialText = unescape(stInitialText);
		else
			stInitialText = '';
			
		var sign = new Sign(stInitialText);
		$('#q').val(stInitialText);
		
		$('#query').submit(
			function()
			{
				sign.SetText($('#q').val());
				return false;
			});
			
		
		
		$('#image-container').append('<img src="transparent.gif" id="overlay" useMap="#themap"/>');
		$('#image-container').append('<map name="themap" id="themap"></map>');
			
		//http://www.maschek.hu/imagemap/imgmap
		new Person('cactus.png', 115, 170, 382, 1, 
			new Array(
				new Array(20,2,7,36,17,45,15,55,37,96,46,223,19,208,16,263,8,297,10,310,14,325,14,359,12,365,18,370,22,321,35,236,43,254,49,303,49,341,59,377,76,377,76,260,74,152,62,135,57,95,67,70,78,65,57,42,49,41,51,11,49,0,29,-3),
				new Array(39,381,20,396,19,420,26,434,12,441,27,462,33,505,47,528,46,592,40,606,25,597,9,679,14,705,14,742,12,749,23,754,22,696,21,672,36,612,48,681,49,724,55,756,70,758,76,748,72,718,74,700,67,687,77,650,72,575,74,536,60,511,60,470,73,449,76,445,62,428,52,421,56,397,49,383)
			));
		new Person('andras.png', 29, 155, 419, 2,
			new Array(
				new Array(67,11,50,44,56,60,51,68,21,88,0,171,16,242,32,257,36,310,31,375,15,412,45,419,57,400,61,367,61,348,70,246,75,404,109,413,127,406,106,375,102,321,113,237,129,239,136,217,136,142,125,96,108,75,86,58,94,37,88,19),
				new Array(71,432,52,465,55,483,25,504,2,609,23,664,30,717,31,795,18,826,43,832,60,813,70,674,77,819,110,832,123,824,104,794,110,675,125,663,135,645,135,575,122,502,93,482,100,457,94,438,84,431)
			));
		new Person('maya.png', 253, 32, 521, 4,
			new Array(
				new Array(75,122,51,144,63,181,25,210,34,204,42,223,40,264,25,297,24,341,40,358,45,399,27,489,19,508,20,514,46,520,69,496,75,445,72,410,87,362,107,509,153,520,155,503,137,488,143,479,136,441,131,394,136,339,127,273,142,262,146,227,157,220,154,200,137,207,119,183,90,171,94,152,79,124,77,125),
				new Array(74,644,59,654,67,684,66,697,29,725,41,743,39,788,22,819,43,879,44,918,28,1022,19,1036,40,1036,64,1019,69,967,69,932,89,877,107,1028,138,1034,152,1030,138,1007,145,997,132,907,135,869,124,788,142,786,147,746,158,740,149,720,137,729,126,708,94,695,93,674,90,650)
			),
			sign
			);
		new Person('david.png', 172, 165, 394, 8,
			new Array(
				new Array(56,6,44,49,31,74,3,110,11,157,14,332,21,371,12,385,37,395,53,373,48,299,64,235,76,331,66,371,82,387,101,387,108,330,101,291,110,225,104,194,108,161,114,141,117,106,109,73,86,59,79,30,80,7),
				new Array(59,397,41,415,44,451,13,467,-1,524,16,571,21,765,14,778,31,780,48,758,46,687,60,629,72,699,73,738,65,762,78,780,101,779,105,744,110,710,101,685,108,627,106,574,104,560,117,539,118,500,109,466,81,449,81,409,72,396)
			));
		new Person('zape.png', 508, 167, 419, 16,
			new Array(
				new Array(58,9,37,20,42,56,45,65,17,84,7,109,16,138,11,172,27,203,22,264,36,273,36,317,49,361,43,388,68,391,79,379,112,396,128,387,114,364,113,345,116,275,125,265,116,213,123,134,126,114,112,71,80,60,84,34,75,12),
				new Array(60,429,36,441,53,478,16,505,11,544,20,598,30,624,23,677,39,700,36,742,47,782,42,803,54,809,71,808,77,792,112,816,131,807,115,780,118,692,128,683,115,632,127,566,123,542,130,535,113,494,85,482,91,449,76,429)
			));
			
		new Person('encse.png', 402, 179, 384, 32, 
			new Array(
				new Array(54,2,39,16,45,48,10,75,-1,150,17,181,13,292,12,368,45,373,52,348,47,289,57,222,80,297,78,352,96,381,119,378,120,355,110,324,102,187,121,149,121,104,104,63,77,51,82,34,79,9,69,-2,69,-2),
				new Array(52,386,36,412,40,435,14,464,-3,534,20,562,15,635,15,732,12,754,46,758,54,731,48,671,58,605,75,674,78,736,93,761,117,759,120,739,113,706,103,565,120,531,120,491,102,449,77,439,84,415,82,392,67,382,67,382)
			)
		);
		
    });
	function UpdateLink()
	{
		var uri = location.href.substring(0,location.href.lastIndexOf('/')+1)+'?';
		if(stText != '')
			uri += 'q='+escape(stText);
		if(mask != 0)	
			uri += '&t='+mask;
		
		$('#permalink').html('<a href="'+uri+'">'+uri+'</a>');
	}
</script>


<style>
a img, img a, img{
	border:none;
}
#image-container{
	position:relative;
	width: 672px;
	margin: 0 auto;
}
.person{
	overflow:hidden;
	position:absolute;
	background:transparent;
	
}
.person img
{
	position:relative;
	/*cursor: pointer;*/
}
#sign{
	position:absolute;
	background:transparent;
	padding:5 10px;
	z-index:10;
}
#overlay{	
	position:absolute;
	top:0;
	left:0;
	width:672px;
	height:591px;
	z-index:20;
}
</style>
</head>
<body>
	<?php csv_header(); ?>

	<h2>ISC Team Editor</h2>
	<form id="query">
		<input type="text" size="10" id="q" value="Ide írj valamit!" />
		<input type="submit"  value="Mondjad" />
	</form>
	<div id="image-container">
		<img src="team.jpg"/>
	</div>
	<p class="center">permalink: <span id="permalink"></span></p>

	<?php csv_footer(); ?>
</body>
</html>
