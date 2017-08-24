# Elm App in a Drupal Block

- Drupal version 8.3
- Elm version 0.18



## Try it out

Clone this repository to your [Drupal 8](https://www.drupal.org/download) site's `/modules` directory:

```bash
$ git clone https://github.com/annabansi/elm_drupal_block.git
```
Install the module, and place the Elm block e.g. into the Sidebar second.



## Development process

First of all you need these projects installed:

- [Drupal Console](https://docs.drupalconsole.com/en/index.html) for generating boilerplate code
- [Elm](https://guide.elm-lang.org/install.html) for compiling Elm application



#### Drupal Module

Generate a new module, and answer the questions according to the following settings:

```bash
$ drupal generate:module
```

settings:
```bash
  --module="Elm Drupal Block"
  --machine-name="elm_drupal_block"
  --module-path="/modules/custom"
  --description="Singleton block with an Elm App in it"
  --core="8.x"
  --package="Custom"
  --module-file="no"
  --features-bundle="no"
  --composer="no"
  --dependencies="no"
  --test="no"
  --twigtemplate="no"
```

It will generate `/elm_drupal_block/elm_drupal_block.info.yml` file for you.


#### Drupal Block

Generate a block to that module, and answer the questions according to the following settings:

```bash
$ drupal generate:plugin:block
```

settings:
```bash
  --module="elm_drupal_block"
  --class="ElmBlock"
  --label="Elm block"
  --plugin-id="elm_block"
  --theme-region=""
  --inputs=""
  --services="no"
```

You get the `/elm_drupal_block/src/Plugin/Block/ElmBlock.php` file.


Clear the caches:

```bash
$ drupal cr all
```

And now, go to your site, install the module, and place the block e.g. into the Sidebar second.


#### Elm App

Create `elm` directory, and install the needed packages:

```bash
$ cd elm_drupal_block
$ mkdir elm
$ cd elm
$ elm-package install -y
```

Create `/elm/src/Main.elm` file as follows:

```elm
module Main exposing (main)
import Html exposing (Html, text)

main : Html msg
main =
    text "Hello, World!"
```


Compile Elm to JavaScript, and place it to `/elm/build/elm-main.js` using the following command:

```bash
$ elm-make src/Main.elm --output=build/elm-main.js
```

Now, load the generated JavaScript code. Add `/elm/elm-app.js` file with the following content:

```javascript
var node = document.getElementById('elm-block');
var app = Elm.Main.embed(node);
```


#### Embed JavaScript to Block

Unfortunately there is no command for generating Drupal libraries, so we have to create the file manually.
Add `/elm_drupal_block/elm_drupal_block.libraries.yml` file with the following content:

```yml
elm_block_js:
  js:
    elm/build/elm-main.js: {preprocess: false}
    elm/elm-app.js: {preprocess: false}
```


Finally, modify the `/elm_drupal_block/src/Plugin/Block/ElmBlock.php` file as follows:

```php
// ...

public function build() {
  $build = [];
  $build['elm_block']['#markup'] = '<div id="elm-block"></div>';
  $build['elm_block']['#attached']['library'] = array( 'elm_drupal_block/elm_block_js', );

  return $build;
}

// ...
```


#### Check your site with a running Elm App in it!
