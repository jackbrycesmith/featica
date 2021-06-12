---
title: Act on feature state in PHP
---

# Act on feature state in PHP 

Featica seeks to provide clean helper methods to make using feature flags in PHP 👨‍🍳 💋👌  ✨<span class="sr-only">.</span>

Other feature flag solutions tend to provide helpers that nudge you to write if statements.

Whilst its OK to do this, I think you'll get better mileage out of using `Featica::when`...

## Using the `Featica::when` helper

> <span class="mr-1">🔮</span> Imagine you're working on a new page design that isn't quite ready for prime time, but you still want to ship to production...

Here's a snippet of using the `when` helper to return different page responses...

```php
return Featica::when(
    feature: 'new-welcome-page',
	for: Auth::user(),
	on: fn() => Inertia::render('NewWelcomePage'),
	off: fn() => Inertia::render('WelcomePage'),
);
```

This helper takes advantage of named parameters introduced in PHP 8 to make for a better looking if statement.

Let's take a look at it further...

### Specifying the `feature`

This one's straightforward, you need to pass the feature you want to check access to. This should be defined in your [FeaticaServiceProvider](./define-laravel-feature-flags-service-provider).

> <span class="mr-1">ℹ️</span>  If the flag hasn't been defined for whatever reason, it will be treated as being in the 'off' state & evaluate the given `off` parameter.

```php{2}
return Featica::when(
	feature: 'new-welcome-page',
	on: fn() => Inertia::render('NewWelcomePage'),
	off: fn() => Inertia::render('WelcomePage'),
);
```

### Passing the `model`, or not...

You have the option to pass a [feature flag owning model](./setup-models-that-own-feature-flags) to this helper to check for access to a given feature. If you have [setup the default state](./define-laravel-feature-flags-service-provider) of this feature to be 'off', you can go to the [nice provided dashboard UI](./dashboard) to turn this flag on for you & other lucky users. This will override the default state.

```php{3}
return Featica::when(
	feature: 'new-welcome-page',
	for: Auth::user(),
	on: fn() => Inertia::render('NewWelcomePage'),
	off: fn() => Inertia::render('WelcomePage'),
);
```

### Act differently based on feature is `on` or `off`

You can decide how what should be returned based on the evaluated feature state. Whilst it's useful in this case to provide a `Closure` to be called, it's not a requirement—you can provide any other plain value to be returned. They can also be combined or omitted freely.

```php{4-5,10-11,16,21}
return Featica::when(
	feature: 'new-welcome-page',
	for: Auth::user(),
	on: fn() => Inertia::render('NewWelcomePage'),
	off: fn() => Inertia::render('WelcomePage'),
);

$pageComponent = Featica::when(
	feature: 'new-welcome-page',
	on: 'NewWelcomePage',
	off: 'WelcomePage'
);

$result = Featica::when(
	feature: 'new-welcome-page',
	off: 'Some value when off'
);

Featica::when(
	feature: 'new-welcome-page',
	on: fn() => doSomethingOnlyWhenOn()
);
```
