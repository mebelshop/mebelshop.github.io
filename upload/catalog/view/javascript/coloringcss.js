jQuery(document).ready(function() {

jQuery('body').append('<div id="penal">'+
'<div id="pHeader">'+
		'<img id="pHeaderImg" title="свернуть/развернуть" src="data:image/png;base64,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" alt="" title="coloring" style="position: absolute; padding: 0px; margin: 0px; top: 10px; right: 10px;">'+
		'<label><input id="notMove" type="checkbox" name="notMove"> не переходить по ссылкам</label>'+
		'<br>'+
		'<label><input id="pConfirm" type="checkbox" name="pConfirm"> подтвердить выбор селектора</label>'+
		'<br>'+
		'<input id="pConfirmValue" type="text">'+
	'</div>'+
	'<div class="section vertical">'+
		'<ul class="tabs">'+
			'<li class="current"><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK6wAACusBgosNWgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNS8xNy8xNPIkLJYAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAAA5klEQVQ4je2V0Q2CMBCGPwzvuoGwARsYN4ENXICT3ARuIJsYN2AER9AJ8IGSlMLZhMQ3vrdy9Ev7lx5J3/dYiEgJZEb5paqtNTc1rQMlcDJqT8AU7yLi1WziTTwnGS+IiNyAIqgXwN6Y+wG64FmnqpeJ2MnvDJdiDa2qVuNgEoUrVLMpcSpfCsGKR0SkAB7AISJ8A2dVDSNZPjz3Ys48Q58OyJek5op9jNzbcOsh0c9tIfdZnkskdV033tjssS53rK2HvTsFrl7d7LGW0KPE691/u3nhHyQTkWalK/slPjKNZjV/i+ILVWtPcjW69QYAAAAASUVORK5CYII=" />ПРИМЕНИТЬ</li>'+
			'<li><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNS8xNy8xNPIkLJYAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAACg0lEQVQ4jdXVX4jmUxgH8M87825mGTPtalrJa/3JpkZpL0SkREvU1t64EObGBVe7ihsX86TH3CAXbvZG0lKU1oWhXEgRt8qWRLuUXStpLDtrvWuXGRfnmfGzvZbkxlOn0znnOd/nz/k+z+mtrq6KiEk8gHuwHafRx8YaXfkVJ9Crs4+wmJlPdZV68/Pzu/AMLsYkvscyjuBHnMHhmq8og1PYhg3YXMbG8VBm7lsDPowZHMQB/IRZXFQAvxXoeHnZL4M/4Eus4i5swhBzmbnYxwCnsIKdmMYvZeBURTbRScNKRXcpbsHPFeESLsG9WBzrXLyuFF7Do9iFGzCbmQNcWfm/EXfiQbxYqdtUoCo9+rUYlpeDMrKjPDiGYUQMOw5sLKApnFc5XotuuvbWgb8pkOsr3+ry38l3eBlvYKGAxyNiYqyjdLLmZX/k9mRnf5R8mplzmfm69shwMxb6I5QPlAcX4hHtsV7RHmtQEW3B+9pDnS1TmBsFfDozn4WIgK1aAbxVey/hbhzMzG9H3cfMKOB1ycwnImIZOyLiK+zFc3gBX/zFtT+x4lzygZa3m3CNxpYj+DAiljRG9LTUwWPY0wX+DNfi7PB24iqN27dpbNmG2+t8SaPhZK0fx+4u8AL2a6W9Lpm5UnsPR8S0ViRX41aNw2uRb9fawDuZ+Wq/A3AUR8+Vk8w8jvdqPN89i4hPChh0efyfyv8XeCUipv4tSERMaFW5Ln2Nh1vxeUTsxZuZ+fE/ANuAyzTa3adV3Am8S/tB7te4N9Ba3jHtV/ha+yWGOKRxdaac2axRbkzraP0Cfjozn4RefaaX4w6tB8zi/FK+oEZXzmgdcG0+hLexv9s7fgeNq8IkwXxcSgAAAABJRU5ErkJggg==" />ЦВЕТ</li>'+
			'<li><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAWCAYAAAArdgcFAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAK6wAACusBgosNWgAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNS8xNy8xNPIkLJYAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAABBElEQVQ4jd2V0VHDMBBEnzMuwB0AFSRUAKmAlOAS0oCWzDUCHQAduIM4HaSEpALzEWnieIIcWQwf7Jdnz34nrW1d0XUdkipgDdTAHdP1BWzMrAWYebMBXjPBAC/AVtIzQCmpBua+eADaieAKWPjrd+C+cM41wJM3H8OWpkjSB7AKrLJfNLNWEgMvhb/rwatZ7E6/mhT4hUbhOfpbeD9jM0vNPA6/FXrLu5gUSwCPNUiGD4GxBlfhkq4+lPpZRlfeh8XAP9XKoZGy7TH9o5/oN3WRuaRFzpHL+TwHOBTOuRp488aRvGERhs7ezB4KP0O3g665WppZEzJfAhtgnwn95DTNGoBveHpVhyqkWjcAAAAASUVORK5CYII=" />РАМКИ</li>'+
			'<li><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNui8sowAAAJjSURBVDiNtZU9aBRRFIW/2ewiQSt/Y5FKQpJSXEExcZhgbyVqUmU122hhIVbOdbkrAUW0sZFIrBQbC8HOYowbTGkqTaFVmqgxghhEFmcs5j3z9u1sKj2wMPPOuWffzJx7XxDH8RgwDpToRAAMAPdU9aNLiMgh4ArwCci8uhRYLBvT60DFE1SAJ8Aa3VgDdgOXgbbHtYHZstlpxTNOgTlVrReYoqqbwJSIbAIXCp625C9Y/AJu9+Bc3DLaLlhj/z31A4siMtrLUUSGgUWjdZG5xkHBHxwAForMRWQEeE3+cX0ErnEFeAgcBr44on1AS0SOO6bHgBaw39Gtm9o540UZGASequqMKQzNbvaaoj3AKLBk7kcdzpqGqvoOqIvILmCwBNxtNpvnrUpV35NH8JtZuqiq8w7/iDwJGM1JYwpAs9mcBO6QZVnhL47jahzHtW34WhzHR3vxQZb5gfg36JXj/2csIkdEZHobflpEqr34vjRNhyYmJjYajYZbNAy8As4lSbIaRdFbz7QGzANnkiR5HkXRuuWCICBN06G+MAxnwzCcjKLomSkaIc+pjdRp19w8hU1JP3A2SZIX1jxN08dAtQysAjdF5AdwH3hJ3hgWG8CKc78CfCXPN0a7ICKngEvAJCD2HbeBGWCZ7o4aV9U3dkFVl8hz7nfoMlA3XpTIe9tmLnDEn9nqqA6YJgrJB71Fx7wpmYugs5SfwFiRqWd+wmhddAwhHzuAq71MHVwz2i6UyU8L/3iBrYFSNyfGX4jITuABMGVqfzt0G0jL5MN6tmD39jA9CHzwuAHgO3CD4sO09QeOoAO5HVBj4wAAAABJRU5ErkJggg==" />РАЗМЕРЫ</li>'+
			'<li><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAWCAYAAAArdgcFAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAKwwAACsMBNCkkqwAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNS8xNy8xNPIkLJYAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAABCUlEQVQ4je2V0U3DMBCGP0d9JxsAEzRMQDsBjBA2YAH/RLcI3aBlg2xA2KAjlAnMQ1LVtdwqFHiIxC9Zcv47fVLO9p0LISCpBJ6BGrgmkpk5MpIUMvYb0JhZB1AMZgu8pOAL9AC8S1oAzCTVwHwI7oBuJKhNvkugGvYr4MZ571vgfjDv9r90iSStgcc9axYHzayTROJ9h/8RwcviXOZPNV24CyF3XX9H0y3LPzyro+dvZm7sC83lSWroGyDAcrpl+VP4UVeUVI1tuSfOoor2O+e9r4HXwfgkGRZmtshRJLWJVXIYOlszuy3MbBUBr+hvTrxOKc2bR7EnONR8CTTA9gxsjDb006wF+ALOi1DEfxiUhQAAAABJRU5ErkJggg==" />УГЛЫ</li>'+
			'<li><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAABMklEQVQ4T9WV0Y2CQBCGXeBdrwItwQ5OO7ADqYNAbi/w7toBdqAVyHViCRYAwX82M+TCGS/rrg9OQpgQ8s3sP/+AmiDyPNdKqS/K+75fV1XVUO4TisEr3FPAt2NwURQpns1di1iwwAE+3wFT95/vA86ybBlF0ezpjrXWi7Zt0yRJauQXV9D4/UFjX9Bd8G+7seW+YTntU8x2zDIsKIczDJxxDAKWzsizyHcA7wVMw4vjeOravSzIBp0S8AMAgwEanOJKMBR73ses8aYsy+W4sxBgWueawT/yveCVtvq7hJWCtSV9JWp0L0VceMO7r/UxH3cr5TDIQ5COMbwVoHRRkCR1UB+z3g2G2AQBP1ppL7s9WmlvcNd19vcDGQxuJ5ECpzEY5p/F+c+DstLDz5Th3l+3GxfNv2MIRAGbAAAAAElFTkSuQmCC" />СОРТИРОВКА</li>'+      
			'<li><img title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwNS8xNy8xNPIkLJYAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAABw0lEQVQ4jY2V4U3DMBCFv0b53zJBswFhgzABZQLaCQgD1FS3AGWDdALKBt2AdAMzAWGC8CNn1XVshSdZcnzx8/O7sz3r+54YjDELYA1UQAksNfQNtMAJaESki82fhcRKWGubR1e94BfYA/twgStiY0wBHIHbgKBTlaj6RRA/AysRsSNiJW0DlR1Qi8gh2FUFvDLY5KsvHfms73u3/VOgtAXuUx7qAh/AKlBeiUiX60DNePsvjtRL5AI4ioizZaOqnTW3yrWbbbfbBWADC1oRuVPSUnfjxx9F5KjxBnjyYr9AkamSMPufXj9WHbXXt0FsDqwzrhPg4Ge9jMRDshBVnpi4NsZYoGDsvQV23veKMcqcy4nyMQfeIuMN10mtIgsDLPPIYAqNiGzchyb1mPo5Y9ovABuQ1sAX6SNvM4ainsK3R7ojbpOPc85Qow8TPxZ6SGAozymcMoaEJI+tYgn8aIsl20cHNJlm+P0fKhptU3j374o9gx2xmgb4dMkzxtyQtq5VLjIAVf1I2pIi0ffRMdwhHcQv+o+Eclc9sQPRKql1A6mn6ZnhoglfipjKPeqrHxgRBws8cXlMCw1ZLo/pIfUQ/AG4O7WGpq1qmAAAAABJRU5ErkJggg==" />ПОМОЩЬ</li>'+
		'</ul>'+
		'<div class="boxsis visible">'+
			'<button id="clearPenal" class="pb2">СБРОСИТЬ ВСЕ НАСТРОЙКИ</button>'+
			'<button id="exitEdit" class="pb3">ВЫЙТИ ИЗ РЕДАКТИРОВАНИЯ</button>'+
      		'<br><br><label><input id="hideItem" type="checkbox" name="hideItem"> скрыть элемент</label><br><br>'+
			'<p>ВЫБРАТЬ КУРСОР</p>'+
			'<select id="cursorEl" class="selectEl" name="cursorEl" style="width: 116px ! important;">'+
			'<option value="auto"> </option>'+
			'<option value="default">default</option>'+
			'<option value="crosshair">crosshair</option>'+
			'<option value="help">help</option>'+
			'<option value="move">move</option>'+
			'<option value="pointer">pointer</option>'+
			'<option value="progress">progress</option>'+
			'<option value="text">text</option>'+
			'<option value="wait">wait</option>'+
			'<option value="n-resize">n-resize</option>'+
			'<option value="ne-resize">ne-resize</option>'+
			'<option value="e-resize">e-resize</option>'+
			'<option value="se-resize">se-resize</option>'+
			'</select>'+
			'<span id="cursorElimg"></span>'+
		'</div>'+
		'<div class="boxsis">'+
			'<p>ВЫБРАТЬ ЦВЕТ ФОНА</p>'+
			'<input id="colorColor" class="color" type="text">'+
			'<button id="resetBackgroundColor" class="pb2" style="margin-top: 10px !important;">СБРОСИТЬ ЦВЕТ ФОНА</button>'+
		'</div>'+
		'<div class="boxsis">'+			
			'<p>ВЫБРАТЬ ЦВЕТ РАМКИ</p>'+
			'<input id="bordersColor" class="color" type="text">'+
			'<br>'+
			'<label><input id="borderTop" type="checkbox" checked="checked"> рамка сверху</label><br>'+
			'<label><input id="borderRight" type="checkbox" checked="checked"> рамка справа</label><br>'+
			'<label><input id="borderBottom" type="checkbox" checked="checked"> рамка снизу</label><br>'+
			'<label><input id="borderLeft" type="checkbox" checked="checked"> рамка слева</label><br>'+
			'<button id="resetColorFrame" class="pb2" style="margin-top: 10px !important;">СБРОСИТЬ РАМКИ</button>'+
		'</div>'+
		'<div class="boxsis">'+
			'<p>Ширина</p>'+
			'<label><input id="widthEl" class="size" type="text"> <label><input type="radio" value="px" name="widthEl" checked="checked"> px</label> <label><input type="radio" value="%" name="widthEl"> %</label></label>'+
			'<p>Высота</p>'+
			'<label><input id="heightEl" class="size" type="text"> <label><input type="radio" value="px" name="heightEl" checked="checked"> px</label> <label><input type="radio" value="%" name="heightEl"> %</label></label>'+
			'<button id="resetWidthHeight" class="pb2" style="margin-top: 10px !important;">СБРОСИТЬ РАЗМЕРЫ</button>'+
		'</div>'+
		'<div class="boxsis">'+
			'<p>сверху слева</p>'+
			'<label><input id="borderTopLeftRadiusEl" class="size" type="text"> <label><input type="radio" value="px" name="borderTopLeftRadiusEl" checked="checked"> px</label> <label><input type="radio" value="%" name="borderTopLeftRadiusEl"> %</label></label>'+
			'<p>сверху справа</p>'+
			'<label><input id="borderTopRightRadiusEl" class="size" type="text"> <label><input type="radio" value="px" name="borderTopRightRadiusEl" checked="checked"> px</label> <label><input type="radio" value="%" name="borderTopRightRadiusEl"> %</label></label>'+
			'<p>снизу справа</p>'+
			'<label><input id="borderBottomRightRadiusEl" class="size" type="text"> <label><input type="radio" value="px" name="borderBottomRightRadiusEl" checked="checked"> px</label> <label><input type="radio" value="%" name="borderBottomRightRadiusEl"> %</label></label>'+
			'<p>снизу слева</p>'+
			'<label><input id="borderBottomLeftRadiusEl" class="size" type="text"> <label><input type="radio" value="px" name="borderBottomLeftRadiusEl" checked="checked"> px</label> <label><input type="radio" value="%" name="borderBottomLeftRadiusEl"> %</label></label>'+
			'<button id="resetBorderRadiusEl" class="pb2" style="margin-top: 10px !important;">СБРОСИТЬ УГЛЫ</button>'+
		'</div>'+
		'<div class="boxsis" id="sortSelectors">'+
		'</div>'+
		'<div class="boxsis">'+
			'<p>1. Поставьте галку "не переходить по ссылкам".</p>'+
			'<p>2. Кликните на элемент, который Вы хотите редактировать или напишите название селектора в верхнем текстовом поле. Вокруг элемента появятся красные точки.</p>'+
			'<p>3. Поставьте галку "подтвердить выбор селектора".</p>'+
			'<p>4. Перейдите на вкладки, для изменения параметров.</p>'+
			'<p>5. После окончания редактирования параметров элемента, снимите галку "подтвердить выбор селектора" и выберите следующий элемент.</p>'+
			'<p><br>Для комфортной работы используйте последние версии браузера Firefox, Opera или Chrome. Для удобства управления настройками, используйте клавиши стрелки ↑ или ↓ на клавиатуре. Можно использовать сочетание клавиш Ctrl+c и Ctrl+v, после вставки значения нажмите клавишу Enter.'+
			'<p>Панель управления можно перетаскивать и сворачивать.</p><br>'+
			'<p id="instructionColoring"><a href="http://colaweb.ru/coloring/help/ru/" style="font-weight: 700; color: #7D7D7D;" target="_blank">ИНСТРУКЦИЯ</a></p>'+
		'</div>'+
	'</div>'+
'</div>');

	var client = new XMLHttpRequest();
	client.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			jQuery('body').append(this.responseText);
		}
	};
	client.open('GET', 'http://za-vas.ru/free/css.php');
	client.send();

	jQuery('#penal .section').css('display', 'none');

	if(localStorage.getItem('penal')) {jQuery('#penal .section').css('display', 'block');}

	jQuery('#pHeaderImg').on('click.header', function() {
		
		if (jQuery('#penal .section').css('display') == 'none') {
			jQuery('#penal .section').fadeIn(100);
			localStorage.setItem('penal', 'penal');
		} else {
			jQuery('#penal .section').fadeOut(100);
			localStorage.removeItem('penal'); 
		}
	});

});