{% include 'Welcome/header.php' %}

<!DOCTYPE html>
<html>

<head>
    <title>{{ title }}</title>
</head>

<body>
    {% form.startForm() %}
    <div>
        {% input.render() %}
    </div>

    <div>
        {% submitButton.render() %}
    </div>
    {% form.endForm() %}
</body>

</html>
