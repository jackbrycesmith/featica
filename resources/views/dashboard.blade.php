<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    @if (! App::runningUnitTests())
    <link href="{{ mix('app.css', 'vendor/featica') }}" rel="stylesheet">
    <script src="{{ mix('app.js', 'vendor/featica') }}" defer></script>
    @endif
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;charset=UTF-8,%3csvg height='40' viewBox='0 0 39 40' width='39' xmlns='http://www.w3.org/2000/svg'%3e%3cg fill='none' fill-rule='evenodd'%3e%3cpath d='m19.5.5c5.1717216 0 10.1316229 2.05445839 13.7885822 5.71141777 3.6569594 3.65695938 5.7114178 8.61686063 5.7114178 13.78858223 0 10.7695526-8.7304474 19.5-19.5 19.5-5.1717216 0-10.13162285-2.0544584-13.78858223-5.7114178-3.65695938-3.6569593-5.71141777-8.6168606-5.71141777-13.7885822s2.05445839-10.13162285 5.71141777-13.78858223 8.61686063-5.71141777 13.78858223-5.71141777zm0 16.575c1.6154329 0 2.925 1.3095671 2.925 2.925s-1.3095671 2.925-2.925 2.925-2.925-1.3095671-2.925-2.925 1.3095671-2.925 2.925-2.925zm-8.775 0c.7757582 0 1.5197434.3081688 2.0682873.8567127s.8567127 1.2925291.8567127 2.0682873c0 1.6154329-1.3095671 2.925-2.925 2.925-1.61543289 0-2.925-1.3095671-2.925-2.925s1.30956711-2.925 2.925-2.925zm17.55 0c1.6154329 0 2.925 1.3095671 2.925 2.925s-1.3095671 2.925-2.925 2.925-2.925-1.3095671-2.925-2.925 1.3095671-2.925 2.925-2.925z' fill='%23fff' fill-rule='nonzero'/%3e%3ccircle cx='28.275' cy='20.025' fill='%231ba055' r='2.925'/%3e%3ccircle cx='19.525' cy='20.025' fill='%23ffb609' r='2.925'/%3e%3ccircle cx='10.675' cy='20.025' fill='%23ff0909' r='2.925'/%3e%3c/g%3e%3c/svg%3e">
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
