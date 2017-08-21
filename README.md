# Elm App in a Drupal Block

- Drupal version 8.3
- Elm version 0.18



## Try it out

Clone this repository to your Drupal site's `/modules` directory:

```bash
$ git clone https://github.com/annabansi/elm_drupal_block.git
```
Install the module, and place the Elm block e.g. to the Sidebar second.



## Development process

Use [Drupal Console](https://docs.drupalconsole.com/en/index.html) for generating boilerplate code.


#### Drupal module

Generate a new Drupal module, and answer the questions according to the following settings:

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
  --composer="yes"
  --dependencies="no"
  --test="no"
  --twigtemplate="no"
```

It will generate `/elm_drupal_block/elm_drupal_block.info.yml` and `/elm_drupal_block/composer.json` files for you.


#### Drupal Block

Generate a block in that module, and answer the questions according to the following settings:

```bash
$ drupal generate:plugin:block
```

settings:
```bash
  --module="elm_drupal_block"
  --class="ElmBlock"
  --label="Elm block"
  --plugin-id="elm_drupal_block"
  --theme-region=""
  --inputs=""
  --services="no"
```
You get the `/elm_drupal_block/src/Plugin/Block/ElmBlock.php` file.


Clear the caches:

```bash
$ drupal cr all
```

Finally, go to your site, install the module, and place the block e.g. to the Sidebar second.


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


Compile Elm to JavaScript, and place it to `/elm/build/elm-main.js`:

```bash
$ elm-make src/Main.elm --output=build/elm-main.js
```

Now, load the generated JavaScript code. Add `/elm/elm-app.js` file with the following content:

```javascript
var node = document.getElementById('elm-block');
var app = Elm.Main.embed(node);
```


#### Embed JavaScript to Block

Unfortunately there is no command for generating Drupal libraries, so we have to create it manually.

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


#### Check your site with Elm in it!
