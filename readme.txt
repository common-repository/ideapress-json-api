=== IdeaPress - Turn WordPress into Mobile Apps (Android, iPhone, WinPhone)===
Contributors: ideanotion, michaelsiu
Donate link: http://ideanotion.net/
Tags: android, iOS, apps, mobile, mobile app, web app, iPad, iphone, winphone, windows 8.1, windows app, mobile site, native, native app, convert, responsive, responsive theme, ideapress, json, api, json-api, turn wordpress, convert wordpress
Requires at least: 2.8
Tested up to: 3.4
Stable tag: 1.0.0
License: GPLv2 or later

Don't write a single line of code. Turn your wordpress into mobile app in 5 mins. (Android, iOS, winPhone)
== Description ==

<a href="http://ideapress.me/turn-wordpress-into-mobile-app.html">IdeaPress</a> convert your wordpress (both wordpress.com and self hosted site) <strong>into Android, iOS, winphone and windows app!</strong> It is used to compliment Ideapress multi-screen application.

<h3>About IdeaPress</h3>
IdeaPress aims to make the mobile app space more accessible to everyone by providing an avenue for coders and non-coders to develop beautiful apps.

IdeaPress has been endorsed by Microsoft CANADA and it is used by magazines and wordpress shop around the world. It has been showcase in WordCamp 2013 DemoCamp, StartUpTO, DevTO. If you want to turn your wordpress site into iOS, Android and WinPhone, it might be the tool for you.

<h3>Build your own mobile app</h3>
<a href="http://ideapress.me/turn-wordpress-into-mobile-app.html">IdeaPress</a> is an online that convert your site into moible app in 3 steps. WordPress is best known for its <strong>ease-of-use and customization</strong> options. When you're <strong>making your iOS, Android and Winphone apps</strong> with IdeaPress, you get to choose exactly how you want to make your app ranging from the content that's included to the <strong>design and styling</strong>. In addition, we will help you publish to app store.

<h3>Features</h3>
IdeaPress comes with loads of features out of the box
<ul>
<li><h4>Offline Browsing</h4>
<p style="padding-top:20px;">Tired of not being able to read your content you have <strong>no internet</strong>? Solve the problem with IdeaPress! IdeaPress stores the content of your website so that it can be accessed on mobile devices anytime, anywhere!</p>
</li>
<li><h4>Posts and Pages</h4>
<p style="padding-top:30px;">Choose which <strong>categories and pages</strong> you want to include in your apps and even choose different content for platforms, whether it be iOS, Android or Windows Phone. IdeaPress format your content to fit on all devices, so your <strong>posts and pages look good on the go</strong>!</p>
</li>
<li><h4>Bookmarking, Sharing and Searching</h4>
<p style="padding-top:50px;">One of the main reasons to get an app over a mobile website is to take advantage of device native features. IdeaPress apps can harness the power of the devices that they are on across all platforms and let users <strong>search the content of your website, share posts and pages and bookmark articles</strong> for later viewing.</p>
</li>
<li><h4>Dynamic updating</h4>
<p style="padding-top:20px;">While most apps have to be resubmitted to the store when updating, IdeaPress apps can be updated through your dashboard and have <strong>the changes pushed directly to the apps withing minutes without re-publishing</strong>. Users get to benefit from your changes almost instantly instead of waiting for store certification.</p>
</li>

</ul>


This plugin is developed by <a href="http://ideanotion.net">Idea Notion</a>

This plugin is developed base on JSON-API, it will add additional functionality to the original JSON API, slim down the return objects and add additional functionality for Ideapress applications

== Installation ==

1. Upload the `ideapress-json-api` folder to the `/wp-content/plugins/` directory or install directly through the plugin installer.
2. Activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer.


== Documentation ==

This plugin base on Wordpress JSON-API plugin and we modified the plugin to taylor the need of Ideapress multi-screen application. Most of the methods are supported, an additional get_recent_posts_on_hub is being added for smaller return size and Posts Control is dropped. The rest of this section is base on JSON-API documentation.

1. General concepts  
   1.1. Requests  
   1.2. Controllers  
   1.3. Responses  
2. Request methods  
   2.1. Core controller methods  
   2.2. Respond controller methods  
3. Request arguments  
   3.1. Output-modifying arguments  
   3.2. Content-modifying arguments  
   3.3. Using include/exclude and redirects  
4. Response objects  
   4.1. Post response object  
   4.2. Category response object  
   4.3. Tag response object  
   4.4. Author response object  
   4.4. Comment response object  
   4.5. Attachment response object  


== 1. General Concepts ==

== 1.1. Requests ==

Requests use a simple REST-style HTTP GET or POST. To invoke the API, include a non-empty query value for `json` in the URL.

JSON API operates in two modes:

1. *Implicit mode* is triggered by setting the `json` query var to a non-empty value on any WordPress page. The content that would normally appear on that page is returned in JSON format.
2. *Explicit mode* is triggered by setting `json` to a known method string. See *Section 2: Request methods* for a complete method listing.

= Implicit mode examples: =

 * `http://www.example.org/?json=1`
 * `http://www.example.org/?p=47&json=1`
 * `http://www.example.org/tag/banana/?json=1`

= Explicit mode examples: =

* `http://www.example.org/?json=get_recent_posts`
* `http://www.example.org/?json=get_post&post_id=47`
* `http://www.example.org/?json=get_tag_posts&tag_slug=banana`

= With user-friendly permalinks configured: =

* `http://www.example.org/api/get_recent_posts/`
* `http://www.example.org/api/get_post/?post_id=47`
* `http://www.example.org/api/get_tag_posts/?tag_slug=banana`

__Further reading__  
See *Section 3: Request arguments* for more information about request arguments to modify the response.

== 1.2. Controllers ==

The 1.0 release of JSON API introduced a modular controller system. This allows developers to flexibly add features to the API and give users more control over which methods they have enabled.

= The Core controller =

Most of the methods available prior to version 1.0 have been moved to the Core controller. The two exceptions are `submit_comment` and `create_post` which are now available from the Respond and Posts controllers, respectively. The Core controller is the only one enabled by default. All other functionality must be enabled from the JSON API Settings page (under Settings in the WordPress admin menu).

= Specifying a controller =

There are a few ways of specifying a controller, depending on how you are calling the API:

* `http://www.example.org/?json=get_recent_posts` (`core` controller is implied, method is `get_recent_posts`)
* `http://www.example.org/api/info/` (`core` controller is implied)
* `http://www.example.org/api/core/get_category_posts/` (`core` controller can also be explicitly specified)
* `http://www.example.org/?json=respond.submit_comment` (`respond` controller, `submit_comment` method)

__Legacy compatibility__  
JSON API retains support for its pre-1.0 methods. For example, if you invoke the method `create_post` without a controller specified, the Posts controller is chosen instead of Core.

= Available controllers =

The current release includes three controllers: Core, Posts, and Respond. Developers are encouraged to suggest or submit additional controllers.

__Further reading__  
See *Section 2: Request methods* for a complete reference of available controllers and methods. For documentation on extending JSON API with new controllers see *Section 5.2: Developing JSON API controllers*.

== 1.3. Responses ==

The standard response format for JSON API is (as you may have guessed) [JSON](http://json.org/).

Here is an example response from `http://localhost/wordpress/?json=1` called on a default WordPress installation (formatted for readability):

    {
      "status": "ok",
      "count": 1,
      "count_total": 1,
      "pages": 1,
      "posts": [
        {
          "id": 1,
          "type": "post",
          "slug": "hello-world",
          "url": "http:\/\/localhost\/wordpress\/?p=1",
          "title": "Hello world!",
          "title_plain": "Hello world!",
          "content": "<p>Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!<\/p>\n",
          "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!\n",
          "date": "2009-11-11 12:50:19",
          "modified": "2009-11-11 12:50:19",
          "categories": [],
          "tags": [],
          "author": {
            "id": 1,
            "slug": "admin",
            "name": "admin",
            "first_name": "",
            "last_name": "",
            "nickname": "",
            "url": "",
            "description": ""
          },
          "comments": [
            {
              "id": 1,
              "name": "Mr WordPress",
              "url": "http:\/\/wordpress.org\/",
              "date": "2009-11-11 12:50:19",
              "content": "<p>Hi, this is a comment.<br \/>To delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.<\/p>\n",
              "parent": 0
            }
          ],
          "comment_count": 1,
          "comment_status": "open"
        }
      ]
    }

== 2. Request methods ==

Request methods are available from the following controllers:

* Core controller - basic introspection methods
* Posts controller - data manipulation methods for posts
* Respond controller - comment/trackback submission methods

== 2.1. Core controller methods ==

The Core controller offers a mostly-complete set of introspection methods for retrieving content from WordPress.


== Method: info ==

Returns information about JSON API.

= Optional arguments =

* `controller` - returns detailed information about a specific controller

= Response =

    {
      "status": "ok",
      "json_api_version": "1.0",
      "controllers": [
        "core"
      ]
    }

  
= Response =

    {
      "status": "ok",
      "name": "Core",
      "description": "Basic introspection methods",
      "methods": [
        ...
      ]
    }

== Method: get_recent_posts ==

Returns an array of recent posts. You can invoke this from the WordPress home page either by setting `json` to a non-empty value (i.e., `json=1`) or from any page by setting `json=get_recent_posts`.

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }

== Method: get_recent_posts_on_hub ==

Returns an array of recent posts. You can invoke this from any page by setting `json=get_recent_posts_on_hub`. This function slims down the content because on hub page, multi-screen app doesn't need the full content and it remove the return comments to decrease the size of return item.

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }

== Method: ideapress_server_ping ==  
Returns true. Make sure the the server has installed the plugin
= Response =

    {
      "status": "ok",
      "result": true
    }


== Method: get_post ==

Returns a single post object.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a post URL
* `id` or `post_id` - set to the post's ID
* `slug` or `post_slug` - set to the post's URL slug

= Optional arguments =

* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "post": { ... }
    }


== Method: get_page ==

Returns a single page object.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a page URL
* `id` or `page_id` - set to the page's ID
* `slug` or `page_slug` - set to the page's URL slug

= Optional arguments =

* `children` - set to a non-empty value to include a recursive hierarchy of child pages
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "page": { ... }
    }

== Method: get_date_posts ==

Returns an array of posts/pages in a specific date archive (by day, month, or year).

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a date archive page
* `date` - set to a date in the format `YYYY` or `YYYY-MM` or `YYYY-MM-DD` (non-numeric characters are stripped from the var, so `YYYYMMDD` or `YYYY/MM/DD` are also valid)

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }

== Method: get_category_posts ==

Returns an array of posts/pages in a specific category.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a category archive page
* `id` or `category_id` - set to the category's ID
* `slug` or `category_slug` - set to the category's URL slug

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "category": { ... }
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_tag_posts ==

Returns an array of posts/pages with a specific tag.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a tag archive page
* `id` or `tag_id` - set to the tag's ID
* `slug` or `tag_slug` - set to the tag's URL slug

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "tag": { ... }
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_author_posts ==

Returns an array of posts/pages written by a specific author.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on an author archive page
* `id` or `author_id` - set to the author's ID
* `slug` or `author_slug` - set to the author's URL slug

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "author": { ... }
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_search_results ==

Returns an array of posts/pages in response to a search query.

= One of the following is required =

* Invoking the JSON API implicitly (i.e., `?json=1`) on a search results page
* `search` - set to the desired search query

= Optional arguments =

* `count` - determines how many posts per page are returned (default value is 10)
* `page` - return a specific page number from the results
* `post_type` - used to retrieve custom post types

= Response =

    {
      "status": "ok",
      "count": 10,
      "count_total": 79,
      "pages": 7,
      "posts": [
        { ... },
        { ... },
        ...
      ]
    }


== Method: get_date_index ==

Returns both an array of date page permalinks and a tree structure representation of the archive.

= Response =

    {
      "status": "ok",
      "permalinks": [
        "...",
        "...",
        "..."
      ],
      "tree": {
        "2009": {
          "09": 17,
          "10": 20,
          "11": 7
        }
      }

Note: the tree is arranged by `response.tree.[year].[month].[number of posts]`.


== Method: get_category_index ==

Returns an array of active categories.

= Response =

    {
      "status": "ok",
      "count": 3,
      "categories": [
        { ... },
        { ... },
        { ... }
      ]
    }


== Method: get_tag_index ==

Returns an array of active tags.

= Response =

    {
      "status": "ok",
      "count": 3
      "tags": [
        { ... },
        { ... },
        { ... }
      ]
    }


== Method: get_author_index ==

Returns an array of active blog authors.

= Response =

    {
      "status": "ok",
      "count": 3,
      "authors": [
        { ... },
        { ... },
        { ... }
      ]
    }


== Method: get_page_index ==

Returns a hierarchical tree of `page` posts.

= Response =

    {
      "status": "ok",
      "pages": [
        { ... },
        { ... },
        { ... }
      ]
    }

== Method: get_nonce ==

Returns a WordPress nonce value, required to call some data manipulation methods.

= Required arguments =

* `controller` - the JSON API controller for the method you will use the nonce for
* `method` - the method you wish to call (currently `create_post` is the only method that requires a nonce)

= Response =

    {
      "status": "ok",
      "controller": "posts",
      "method": "create_post",
      "nonce": "cefe01efd4"
    }

__Further reading__  
To learn more about how nonces are used in WordPress, see [Mark Jaquith's article on the subject](http://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/).

== 2.2. Pages controller methods ==

== Method: create_post ==

Creates a new post.

= Required argument =

* `nonce` - available from the `get_nonce` method (call with vars `controller=posts` and `method=create_post`)

= Optional arguments =

* `status` - sets the post status ("draft" or "publish"), default is "draft"
* `title` - the post title
* `content` - the post content
* `author` - the post's author (login name), default is the current logged in user
* `categories` - a comma-separated list of categories (URL slugs)
* `tags` - a comma-separated list of tags (URL slugs)

Note: including a file upload field called `attachment` will cause an attachment to be stored with your new post.


== 2.3. Respond controller methods ==

== Method: submit_comment ==

Submits a comment to a WordPress post.

= Required arguments =

* `post_id` - which post to comment on
* `name` - the commenter's name
* `email` - the commenter's email address
* `content` - the comment content

= Optional arguments =

* `redirect` - redirect instead of returning a JSON object
* `redirect_ok` - redirect to a specific URL when the status value is `ok`
* `redirect_error` - redirect to a specific URL when the status value is `error`
* `redirect_pending` - redirect to a specific URL when the status value is `pending`

= Custom status values =

* `pending` - assigned if the comment submission is pending moderation


== 3. Request arguments ==

API requests can be controlled by specifying one of the following arguments as URL query vars.

= Examples =

* Debug the response: `http://www.example.org/api/get_page_index/?dev=1`
* Widget-style JSONP output: `http://www.example.org/api/get_recent_posts/?callback=show_posts_widget&read_more=More&count=3`
* Redirect on error: `http://www.example.org/api/posts/create_post/?callback_error=http%3A%2F%2Fwww.example.org%2Fhelp.html`

== 3.1. Output-modifying arguments ==

The following arguments modify how you get results back from the API. The redirect response styles are intended for use with the data manipulation methods.

* Setting `callback` to a JavaScript function name will trigger a JSONP-style callback.
* Setting `redirect` to a URL will cause the user's browser to redirect to the specified URL with a `status` value appended to the query vars (see the *Response objects* section below for an explanation of status values).
* Setting `redirect_[status]` allows you to control the resulting browser redirection depending on the `status` value.
* Setting `dev` to a non-empty value adds whitespace for readability and responds with `text/plain`
* Omitting all of the above arguments will result in a standard JSON response.

== 3.2. Content-modifying arguments ==

These arguments are available to modify all introspection methods:

* `date_format` - Changes the format of date values. Uses the same syntax as PHP's date() function. Default value is `Y-m-d H:i:s`.
* `read_more` - Changes the 'read more' link text in post content.
* `include` - Specifies which post data fields to include. Expects a comma-separated list of post fields. Leaving this empty includes *all* fields.
* `exclude` - Specifies which post data fields to exclude. Expects a comma-separated list of post fields.
* `custom_fields` - Includes values from posts' Custom Fields. Expects a comma-separated list of custom field keys.
* `author_meta` - Includes additional author metadata. Should be a comma-separated list of metadata fields.
* `count` - Controls the number of posts to include (defaults to the number specified by WordPress)
* `order` - Controls the order of post results ('DESC' or 'ASC'). Default value is 'DESC'.
* `order_by` - Controls which field to order results by. Expects one of the following values:
  * `author`
  * `date` (default value)
  * `title`
  * `modified`
  * `menu_order` (only works with Pages)
  * `parent`
  * `ID`
  * `rand`
  * `meta_value` (`meta_key` must also be set)
  * `none`
  * `comment_count`
* `meta_key`, `meta_value`, `meta_compare` - Retrieve posts (or Pages) based on a custom field key or value.

== 3.3. Using include/exclude and redirects ==

__About `include`/`exclude` arguments__  
By default you get all values included with each post object. Specify a list of `include` values will cause the post object to filter out the values absent from the list. Specifying `exclude` causes post objects to include all values except the fields you list. For example, the query `exclude=comments` includes everything *except* the comments.

__About the `redirect` argument__  
The `redirect` response style is useful for when you need the user's browser to make a request directly rather than making proxy requests using a tool like cURL. Setting a `redirect` argument causes the user's browser to redirect back to the specified URL instead of returning a JSON object. The resulting `status` value is included as an extra query variable.

For example calling an API method with `redirect` set to `http://www.example.com/foo` will result in a redirection to one of the following:

* `http://www.example.com/foo?status=ok`
* `http://www.example.com/foo?status=error`

You can also set separate URLs to handle status values differently. You could set `redirect_ok` to `http://www.example.com/handle_ok` and `redirect_error` to `http://www.example.com/handle_error` in order to have more fine-tuned control over the method result.


== 4. Response objects ==

This section describes data objects you can retrieve from WordPress and the optional URL redirects.

__Status values__  
All JSON API requests result in a status value. The two basic status values are `ok` and `error`. Additional status values are available for certain methods (such as `pending` in the case of the `submit_comment` method). API methods that result in custom status values include a *custom status values* section in their documentation.

__Naming compatibility__  
Developers familiar with WordPress may notice that many names for properties and arguments have been changed. This was a stylistic choice that intends to provide more clarity and consistency in the interface.

== 4.1. Post response object ==

* `id` - Integer
* `type` - String (e.g., `post` or `page`)
* `slug` - String
* `url` - String
* `title` - String
* `title_plain` - String
* `content` - String (modified by the `read_more` argument)
* `excerpt` - String
* `date` - String (modified by the `date_format` argument)
* `modified` - String (modified by the `date_format` argument)
* `categories` - Array of category objects
* `tags` - Array of tag objects
* `author` Author object
* `comments` - Array of comment objects
* `attachments` - Array of attachment objects
* `comment_count` - Integer
* `comment_status` - String (`"open"` or `"closed"`)
* `thumbnail` - String (only included if a post thumbnail has been specified)
* `custom_fields` - Object (included by setting the `custom_fields` argument to a comma-separated list of custom field names)

__Note__  
The `thumbnail` attribute returns a URL to the image size specified by the optional `thumbnail_size` request argument. By default this will use the `thumbnail` or `post-thumbnail` sizes, depending on your version of WordPress. See [Mark Jaquith's post on the topic](http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/) for more information.

== 4.2. Category response object ==

* `id` - Integer
* `slug` - String
* `title` - String
* `description` - String
* `parent` - Integer
* `post_count` - Integer

== 4.3. Tag response object ==

* `id` - Integer
* `slug` - String
* `title` - String
* `description` - String
* `post_count` - Integer

== 4.4. Author response object ==

* `id` - Integer
* `slug` - String
* `name` - String
* `first_name` - String
* `last_name` - String
* `nickname` - String
* `url` - String
* `description` - String
  
Note: You can include additional values by setting the `author_meta` argument to a comma-separated list of metadata fields.

== 4.5. Comment response object ==

* `id` - Integer
* `name` - String
* `url` - String
* `date` - String
* `content` - String
* `parent` - Integer
* `author` - Object (only set if the comment author was registered & logged in)

== 4.6. Attachment response object ==

* `id` - Integer
* `url` - String
* `slug` - String
* `title` - String
* `description` - String
* `caption` - String
* `parent` - Integer
* `mime_type` - String
* `images` - Object with values including `thumbnail`, `medium`, `large`, `full`, each of which are objects with values `url`, `width` and `height` (only set if the attachment is an image)

== Frequently Asked Questions ==
1. Why the plugin doesn't work?
It cannot work together with other json api plugin

== Screenshots ==
Screenshot-1.png shows what the returning json looks like
== Changelog ==
Initial checkin
== Upgrade Notice ==
Initial checkin

