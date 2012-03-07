$(document).ready(function()
{
	var liens = $('#navigation li');
	var main = $('#main');
	liens.click(function()
	{
		switch(this.id)
		{
			case 'add_questions':
				main.load("index.php?page=add_questions #main");
				break;
			case 'import_questions':
				main.load("index.php?page=import_questions #main");
				break;
		}
	});
});

