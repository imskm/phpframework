<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Form validation</title>
		<style>
			form {
			/* Just to center the form on the page */
			margin: 0 auto;
			width: 400px;
			/* To see the outline of the form */
			padding: 1em;
			border: 1px solid #CCC;
			border-radius: 1em;
			}

			form div + div {
			margin-top: 1em;
			}

			label {
			/* To make sure that all labels have the same size and are properly aligned */
			display: inline-block;
			width: 90px;
			text-align: right;
			}

			input, textarea {
			/* To make sure that all text fields have the same font settings
			 By default, textareas have a monospace font */
			font: 1em sans-serif;

			/* To give the same size to all text fields */
			width: 300px;
			box-sizing: border-box;

			/* To harmonize the look & feel of text field border */
			border: 1px solid #999;
			}

			input:focus, textarea:focus {
			/* To give a little highlight on active elements */
			border-color: #000;
			}

			textarea {
			/* To properly align multiline text fields with their labels */
			vertical-align: top;

			/* To give enough room to type some text */
			height: 5em;
			}

			.button {
			/* To position the buttons to the same position of the text fields */
			padding-left: 90px; /* same size as the label elements */
			}

			button {
			/* This extra margin represent roughly the same space as the space
			 between the labels and their text fields */
			margin-left: .5em;
}
		</style>
	</head>
	<body>
		<form action="/development/update" method="post">
	<div>
        <label for="name">Post id:</label>
        <input type="text" id="phone" name="post_id" disabled="disabled">
    </div>
	<div>
        <label for="title">Post title:</label>
        <input type="text" id="title" name="title">
    </div>
    <div>
        <label for="content">Content:</label>
        <textarea id="content" name="content"></textarea>
    </div>
	<div class="button">
	  <button type="submit">Send your message</button>
	</div>
</form>

	</body>
</html>
