# Inclass-9-web-pro
About the Project
Campus Crust is a small PHP practice project for learning how to handle
and validate form submissions on the server. The page lets a user enter
a chef name, choose a pizza size and quantity, select optional toppings,
and add a kitchen note. When the form is submitted, PHP checks the
submitted values and displays a practice ticket.

This is a classroom exercise only. It does not save an order, take
payment, or send anything to a real kitchen.
Project Files
- order.html --- Displays the pizza order form and collects the
  user's selections.
- order.php --- Receives the submitted form, validates the
  values, and displays the ticket. User-provided text is escaped
  before it is shown in the page.
Links
- GitHub repository:
  https://github.com/thiruna31/Inclass-9-web-pro.git
- CODD order form:
  https://codd.cs.gsu.edu/\~tpalaniappa1/campuscrust/order.html
Run the Project Locally
1. Make sure PHP is installed on your computer.
2. Open a terminal in the folder containing order.html and
   order.php.
3. Start PHP's local development server:
   php -S localhost:8000
4. Open this address in your browser:
   http://localhost:8000/order.html
Keep the terminal open while testing. To stop the local server, press
Ctrl+C in that terminal.
What to Test
- Submit a normal order with a valid name, pizza size, and quantity.
- Submit an order without selecting any toppings.
- Try an invalid quantity, such as a value outside the allowed range
  of 1--5.
- Test how the server handles an unapproved topping value.
- Confirm the PHP ticket displays the submitted values safely.
Notes on Form Handling
The form's name attributes determine the keys used to access submitted
values in PHP, such as through $_POST. The id attribute can connect
a form control to its label. An unchecked checkbox is not included in
the submitted form data, so the PHP handler should treat missing
optional toppings as an empty list.

Server-side validation is important because browser-side checks can be
bypassed. The PHP handler should verify submitted values against the
allowed options before displaying the ticket.