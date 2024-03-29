<?php
////////////////////////////////////////////////////////////////////////////
//                              SnowCMS v2.0                              //
//                           By the SnowCMS Team                          //
//                             www.snowcms.com                            //
//            Released under the Microsoft Reciprocal License             //
//                 www.opensource.org/licenses/ms-rl.html                 //
////////////////////////////////////////////////////////////////////////////
//                                                                        //
//       SnowCMS originally pawned by soren121 started in early 2008      //
//                                                                        //
////////////////////////////////////////////////////////////////////////////
//                                                                        //
//                  SnowCMS v2.0 began in November 2009                   //
//                                                                        //
////////////////////////////////////////////////////////////////////////////
//                       File version: SnowCMS 2.0                        //
////////////////////////////////////////////////////////////////////////////

if(!defined('INSNOW'))
{
	die('Nice try...');
}

/*
	Class: Atom

	In order to allow plugins and others to quickly create Atom feeds without
	too much hassle, the Atom class allows you to pass data to the class and
	then generate an Atom feed. Also see <RSS>.

	The RSS class is made according to the information found here:
	<http://www.atomenabled.org/developers/syndication/>
*/
class Atom
{
	// Variable: id
	// Identifies the feed using a unique ID, such as a URL to your site.
	private $id;

	// Variable: title
	// The title of the Atom feed.
	private $title;

	// Variable: updated
	// The last time the feed was modified significantly.
	private $updated;

	// Variable: authors
	// An array containing sub arrays with an author name, email address
	// and URI (URL to their home page, like their profile).
	private $authors;

	// Variable: links
	// An array containing links, such as a link to the Atom feed itself.
	private $links;

	// Variable: categories
	// An array containing categories of the Atom feed.
	private $categories;

	// Variable: contributors
	// An array containing contributors to the feed.
	private $contributors;

	// Variable: icon
	// An small image which provides "iconic visual identification" for the
	// feed.
	private $icon;

	// Variable: logo
	// Same as the above, but only larger.
	private $logo;

	// Variable: rights
	// Contains information about the rights of the feed, like copyrights.
	private $rights;

	// Variable: subtitle
	// A subtitle for the Atom feed.
	private $subtitle;

	// Variable: entries
	// An array containing entries for the Atom feed.
	private $entries;

	/*
		Constructor: __construct
	*/
	public function __construct()
	{
		// This will set everything to null and empty.
		$this->clear();
	}

	/*
		Method: clear

		Clears all the feed information.

		Parameters:
			none

		Returns:
			void - Nothing is returned by this method.
	*/
	public function clear()
	{
		$this->id = null;
		$this->title = null;
		$this->updated = null;
		$this->authors = array();
		$this->links = array();
		$this->categories = array();
		$this->contributors = array();
		$this->icon = null;
		$this->logo = null;
		$this->rights = null;
		$this->subtitle = null;
		$this->entries = array();
	}

	/*
		Method: set_id

		Sets the id (universally unique identifier, such as a URL to the home
		page) of the feed.

		Parameters:
			string $id - The id to set for the feed.

		Returns:
			void - Nothing is returned by this method.
	*/
	public function set_id($id)
	{
		$this->id = $id;
	}

	/*
		Method: id

		Returns the currently set id for the feed.

		Parameters:
			none

		Returns:
			string - Returns a string containing the currently set id of the feed.
	*/
	public function id()
	{
		return $this->id;
	}

	/*
		Method: set_title

		Sets the title of the feed.

		Parameters:
			string $title - The title to set for the feed.

		Returns:
			void - Nothing is returned by this method.
	*/
	public function set_title($title)
	{
		$this->title = $title;
	}

	/*
		Method: title

		Returns the currently set title for the feed.

		Parameters:
			none

		Returns:
			string - Returns a string containing the currently set title of the
							 feed.
	*/
	public function title()
	{
		return $this->title;
	}

	/*
		Method: set_updated

		Sets the timestamp for when the last time the feed was last
		significantly updated.

		Parameters:
			int $timestamp - The timestamp of when the feed was last
											 significantly updated.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function set_updated($timestamp)
	{
		if((string)$timestamp == (string)(int)$timestamp)
		{
			$this->updated = (int)$timestamp;
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
		Method: updated

		Returns the currently set timestamp of when the feed was last
		significantly updated.

		Parameters:
			none

		Returns:
			int - Returns an int containing a timestamp which is the last
						time the feed was last significantly updated.
	*/
	public function updated()
	{
		return $this->updated;
	}

	/*
		Method: add_author

		Adds an author to the feed.

		Parameters:
			string $name - The name of the author.
			string $email - The email address of the author.
			string $uri - The URI of the authors home page, such as a link to
										their profile.

		Returns:
			bool - Returns true on success, false on failure.

		Note:
			If entries do not have an author, then you must have an author for
			the entire feed itself.
	*/
	public function add_author($name, $email = null, $uri = null)
	{
		// You must have a name...
		if(empty($name))
		{
			return false;
		}
		else
		{
			$this->authors[] = array(
													 'name' => $name,
													 'email' => $email,
													 'uri' => $uri,
												 );

			return true;
		}
	}

	/*
		Method: authors

		Returns an array containing all the currently added authors.

		Parameters:
			none

		Returns:
			array - Returns an array containing all the currently added authors.
	*/
	public function authors()
	{
		return $this->authors;
	}

	/*
		Method: remove_author

		Removes the author at the specified index.

		Parameters:
			int $index - The index of the author to remove.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function remove_author($index)
	{
		if((string)$index != (string)(int)$index || $index < 0 || $index >= count($this->authors))
		{
			return false;
		}

		if($index == 0)
		{
			// Pop it off the top.
			array_shift($this->authors);
		}
		elseif($index == (count($this->authors) - 1))
		{
			// Take it off the end.
			unset($this->authors[$index]);
		}
		else
		{
			// Somewhere inbetween it appears.
			$authors = array();
			$length = count($this->authors);

			for($i = 0; $i < $length; $i++)
			{
				// Is this what we are deleting?
				if($index == $i)
				{
					// Yup, so skip it.
					continue;
				}

				// Keeping it!
				$authors[] = $this->authors[$i];
			}

			$this->authors = $authors;
		}

		return true;
	}

	/*
		Method: add_link

		Adds a link to the feed.

		Parameters:
			string $href - The href attribute of the link (required).
			string $rel - The relationship type of the link (defaults to
										alternate).
			string $type - The media type of the resource.
			string $title - Human readable information about the link.
			string $hreflang - The language of the referenced resource.
			string $length - The length of the resource, in bytes.

		Returns:
			bool - Returns true on success, false on failure.

		Note:
			Here are some valid options for the $rel parameter:
				alternate - An alternate representation of the feed, like a URL to
										the place of where the feed entries come from or another
										feed (like RSS).
				enclosure - A related resource which is large in size, like an audio
										or video recording.
				related - A document related to the entry or feed.
				self - A URL to the feed itself (recommended by the specification).
				via - The source of information used in the entry.
	*/
	public function add_link($href, $rel = 'alternate', $type = null, $title = null, $hreflang = null, $length = null)
	{
		// The href is required.
		if(empty($href))
		{
			return false;
		}

		$this->links[] = array(
											 'href' => $href,
											 'rel' => $rel,
											 'type' => $type,
											 'title' => $title,
											 'hreflang' => $hreflang,
											 'length' => $length,
										 );
		return true;
	}

	/*
		Method: links

		Returns the currently added links in the feed.

		Parameters:
			none

		Returns:
			array - Returns an array containing all the currently added links in
							the feed.
	*/
	public function links()
	{
		return $this->links;
	}

	/*
		Method: remove_link

		Removes the link at the specified index.

		Parameters:
			int $index - The index of the link to remove.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function remove_link($index)
	{
		// Is the index invalid? Then we cannot remove the link. Sorry.
		if((string)$index != (string)(int)$index || $index < 0 || $index >= count($this->links))
		{
			return false;
		}

		// The first link? Using the unshift function will take the first item
		// of an array off without much hassle.
		if($index == 0)
		{
			array_unshift($this->links);
		}
		// The very last link? Pretty simple too.
		elseif($index == (count($this->links) - 1))
		{
			unset($this->links[$index]);
		}
		else
		{
			// Anywhere inbetween? Not so plain cut and dry.
			// We could simply just delete it, but we want to maintain the
			// sequential ordering of the indexes.
			$links = array();
			$length = count($this->links);

			for($i = 0; $i < $length; $i++)
			{
				if($index == $i)
				{
					continue;
				}

				$links[] = $this->links[$i];
			}

			$this->links = $links;
		}

		return true;
	}

	/*
		Method: add_category

		Adds a category to the feed itself.

		Parameters:
			string $term - The category term.
			string $scheme - "identifies the categorization scheme via a URI."
			string $label - A human readable label for the category.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function add_category($term, $scheme = null, $label = null)
	{
		if(empty($term))
		{
			return false;
		}

		$this->categories[] = array(
														'term' => $term,
														'scheme' => $scheme,
														'label' => $label,
													);
		return true;
	}

	/*
		Method: categories

		Returns the currently set categories of the feed.

		Parameters:
			none

		Returns:
			array - Returns an array containing the categories currently set.
	*/
	public function categories()
	{
		return $this->categories;
	}

	/*
		Method: remove_category

		Removes the category at the specified index.

		Parameters:
			int $index - The index of the category to remove.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function remove_category($index)
	{
		if((string)$index != (string)(int)$index || $index < 0 || $index >= count($this->categories))
		{
			return false;
		}

		if($index == 0)
		{
			array_unshift($index);
		}
		elseif($index == (count($this->categories) - 1))
		{
			unset($this->categories[$index]);
		}
		else
		{
			$categories = array();
			$length = count($this->categories);

			for($i = 0; $i < $length; $i++)
			{
				if($index == $i)
				{
					continue;
				}

				$categories[] = $this->categories[$i];
			}

			$this->categories = $categories;
		}

		return true;
	}

	/*
		Method: add_contributor

		Adds a contributor to the feed.

		Parameters:
			string $name - The name of the contributor.
			string $email - The email address of the contributor.
			string $uri - The URI of the contributors home page, such as a link
										to their profile.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function add_contributor($name, $email = null, $uri = null)
	{
		// A name is required.
		if(empty($name))
		{
			return false;
		}

		$this->contributors[] = array(
															'name' => $name,
															'email' => $email,
															'uri' => $uri,
														);
		return true;
	}

	/*
		Method: contributors

		Returns all the current contributors of the feed.

		Parameters:
			none

		Returns:
			array - Returns an array containing all the current contributors
							of the feed.
	*/
	public function contributors()
	{
		return $this->contributors;
	}

	/*
		Method: remove_contributor

		Removes the contributor at the specified index.

		Parameters:
			none

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function remove_contributor($index)
	{
		if((string)$index != (string)(int)$index || $index < 0 || $index >= count($this->contributors))
		{
			return false;
		}

		if($index == 0)
		{
			array_unshift($this->contributors);
		}
		elseif($index == (count($this->contributors) - 1))
		{
			unset($this->contributors[$index]);
		}
		else
		{
			$contributors = array();
			$length = count($this->contributors);

			for($i = 0; $i < $length; $i++)
			{
				if($index == $i)
				{
					continue;
				}

				$contributors = $this->contributors[$i];
			}

			$this->contributors = $contributors;
		}

		return true;
	}

	/*
		Method: set_icon

		Sets an icon which visually represents the feed, it should be
		small (of course) and square (of course!).

		Parameters:
			string $icon - The URL to the icon.

		Returns:
			void - Nothing is returned by this method.
	*/
	public function set_icon($icon)
	{
		$this->icon = $icon;
	}

	/*
		Method: icon

		Returns the currently set icon for the feed.

		Parameters:
			none

		Returns:
			string - Returns a string containing the currently set icon of
							 the feed.
	*/
	public function icon()
	{
		return $this->icon;
	}

	/*
		Method: set_logo

		Sets a logo which visually represents the feed. This should, in
		reality, be the same image as set in <Atom::set_icon>, only larger.

		Parameters:
			string $logo - The URL to the logo which is being set.

		Returns:
			void - Nothing is returned by this method.
	*/
	public function set_logo($logo)
	{
		$this->logo = $logo;
	}

	/*
		Method: logo

		Returns the currently set logo for the feed.

		Parameters:
			none

		Returns:
			string - Returns a string containing the current set logo of
							 the feed.
	*/
	public function logo()
	{
		return $this->logo;
	}

	/*
		Method: set_rights

		Sets the rights of the feed, this would be something such as a
		copyright.

		Parameters:
			string $rights - The rights of the feed.

		Returns:
			void - Nothing is returned by this method.
	*/
	public function set_rights($rights)
	{
		$this->rights = $rights;
	}

	/*
		Method: rights

		Returns the currently set rights of the feed.

		Parameters:
			none

		Returns:
			string - Returns a string containing the rights of the feed.
	*/
	public function rights()
	{
		return $this->rights;
	}

	/*
		Method: set_subtitle

		Sets the subtitle of the feed.

		Parameters:
			string $subtitle - The subtitle of the feed.

		Returns:
			string - Returns a string containing the subtitle of the feed.
	*/
	public function set_subtitle($subtitle)
	{
		$this->subtitle = $subtitle;
	}

	/*
		Method: subtitle

		Returns the currently set subtitle of the feed.

		Parameters:
			none

		Returns:
			string - Returns the currently set subtitle of the feed.
	*/
	public function subtitle()
	{
		return $this->subtitle;
	}

	/*
		Method: add_entry

		Adds an entry to the feed.

		Parameters:
			string $id - A globally unique identifier for the entry. This id
									 should never change! Ever! It is not recommended you
									 use the link of where the entry came from (permalink),
									 though it is acceptable.
			string $title - A human readable title for the entry.
			int $updated - The timestamp of when the entry was last modified in
										 a significant way.
			array $author - An array containing at least a name (email and uri
											recommended), but you can also specify multiple
											authors as well, in of course, multiple arrays.
			array $content - An array containing the value (value index) of the
											 content tag, but also the type (type index) of the
											 content tag as well. Such as text (default), html
											 or xhtml.
			array $links - An array containing alternative links of the entry,
										 which is where the entry came from. You are required
										 to supply a link (href index) but you can also specify
										 an hreflang (hreflang index) of the link itself.
			array $summary - The same as the $content parameter, except this is
											 for a summary or excerpt of the entry.
			array $categories - An array containing categories of the entry,
													there can be multiple categories. There is one
													required attribute term (term index), which is
													the category, but scheme (scheme index) and
													label (label index) are optional.
			array $contributors - Just like the $authors parameter, but for
														contributors.
			int $published - Contains the timestamp of when the entry was
											 initially created.
			array $source - An array containing information about where the entry
											originated from, if it was taken from another feed.
											It should contain: id, title, updated and rights.
			string $rights - A string containing the rights of the entry itself.

		Returns:
			bool - Returns true on success, false on failure.
	*/
	public function add_entry($id, $title, $updated, $author = array(), $content = array(), $links = array(), $summary = array(), $categories = array(), $contributors = array(), $published = null, $source = array(), $rights = null)
	{
		// You must have at least an id, title and updated time.
		if(empty($id) || empty($title) || (string)$updated != (string)(int)$updated)
		{
			return false;
		}

		// Do the simpler validation stuff.
		// Such as content requiring the value index :P
		if(empty($content['value']) || empty($summy['type']) || ($published !== null && (string)$published != (string)(int)$published) || (count($source) > 0 && (empty($source['id']) || empty($source['title']))))
		{
			return false;
		}
		else
		{
			$content = array(
									 'value' => $content['value'],
									 'type' => empty($content['type']) ? 'text' : $content['type'],
								 );

			$summary = array(
									 'value' => $summary['value'],
									 'type' => empty($summary['type']) ? 'text' : $summary['type'],
								 );

			$source = array(
									'id' => $source['id'],
									'title' => $source['title'],
									'updated' => isset($source['updated']) ? $source['updated'] : null,
									'rights' => isset($source['rights']) ? $source['rights'] : null,
								);
		}

		// Authors not a flat array? Make it into one!
		if(!is_flat_array($author))
		{
			$author = array($author);
		}

		// Same goes for links.
		if(!is_flat_array($links))
		{
			$links = array($links);
		}

		// And categories.
		if(!is_flat_array($categories))
		{
			$categories = array($categories);
		}

		// ... and contributors.
		if(!is_flat_array($contributors))
		{
			$contributors = array($contributors);
		}

		// Now validate each one, then we are done!!!
		if(!$this->has_indexes($author, array('name' => true, 'email' => false, 'uri' => false)) || !$this->has_indexes($links, array('href' => true, 'hreflang' => false)) || !$this->has_indexes($categories, array('term' => true, 'scheme' => false, 'label' => false)) || !$this->has_indexes($contributors, array('name' => true, 'email' => false, 'uri' => false)))
		{
			// Something was invalid!
			return false;
		}

		// Alright, all good.
		$this->entries[] = array(
												 'id' => $id,
												 'title' => $title,
												 'updated' => $updated,
												 'author' => $author,
												 'content' => $content,
												 'links' => $links,
												 'summary' => $summary,
												 'categories' => $categories,
												 'contributors' => $contributors,
												 'published' => $published,
												 'rights' => $rights,
											 );
		return true;
	}

	/*
		Method: entries

		Returns all the current entries of the feed.

		Parameters:
			none

		Returns:
			array - Returns an array containing all the entries of the feed.
	*/
	public function entries()
	{
		return $this->entries;
	}

	/*
		Method: remove_entry

		Removes the entry at the specified index.

		Parameters:
			int $index - The index of the entry to remove.

		Returns:
			bool - Returns true on success, false on failue.
	*/
	public function remove_entry($index)
	{
		if((string)$index != (string)(int)$index || $index < 0 || $index >= count($this->entries))
		{
			return false;
		}

		if($index == 0)
		{
			array_unshift($this->entries);
		}
		elseif($index == (count($this->entries) - 1))
		{
			unset($this->entries[$index]);
		}
		else
		{
			$entries = array();
			$length = count($this->entries);

			for($i = 0; $i < $length; $i++)
			{
				if($index == $i)
				{
					continue;
				}

				$entries = $this->entries[$i];
			}

			$this->entries = $entries;
		}

		return true;
	}

	/*
		Method: has_indexes

		Checks the sub arrays for the specified indexes, and no others.

		Parameters:
			array &$array - The array to check.
			array $indexes - An array containing the allowed indexes, in this
											 format: (index => required).

		Returns:
			bool - Returns true if the array is valid, false if not.

		Note:
			An example for $indexes would be:
				array(
					'data' => true,
					'otherdata' => false,
					'someData' => true,
				)

			If the supplied array did not have the data index, false would be
			returned, same goes for the someData index. However, the otherdata
			index is not required, so if it was not found, it would be added (set
			as null), but if there were any other indexes that aren't listed,
			then they will be removed.
	*/
	private function has_indexes(&$array, $indexes)
	{
		$new_array = array();

		if(count($array) == 0 && count($indexes) == 0)
		{
			return true;
		}
		elseif(count($indexes) == 0)
		{
			return false;
		}
		else
		{
			foreach($indexes as $index => $required)
			{
				if(isset($array[$index]))
				{
					$new_array[$index] = $array[$index];
				}
				elseif(!isset($array[$index]) && empty($required))
				{
					$new_array[$index] = null;
				}
				elseif(!empty($required))
				{
					return false;
				}
			}

			$array = $new_array;
			return true;
		}
	}

	/*
		Method: generate

		Generates an RSS feed according to all the current information
		about the RSS feed. The feed will be displayed unless a file pointer
		is specified, in which case the feed will be written to the specified
		file stream.

		Parameters:
			resource $fp - The stream to write the RSS output to, instead of
										 through echo.

		Returns:
			bool - Returns true if the RSS feed was generated successfully, false
						 if all the information which was required was not supplied, or
						 if the method could not obtain a lock on the supplied file
						 pointer.
	*/
	public function generate($fp = null)
	{
		// We do require some things...
		if(empty($this->id) || empty($this->title) || empty($this->updated))
		{
			return false;
		}
		// Supplied a stream for us to write to?
		elseif(!empty($fp) && !flock($fp, LOCK_EX))
		{
			// It would be nice if we could have an exclusive lock...
			return false;
		}

		api()->run_hooks('atom_generate', array($this, &$fp));

		// We may need to output headers!
		if(empty($fp))
		{
			if(ob_get_length() > 0)
			{
				ob_clean();
			}

			// Well, one ;-) The content type.
			header('Content-Type: application/atom+xml; charset=utf-8');
		}

		// Start filling the buffer.
		$buffer = '<?xml version="1.0" encoding="utf-8"?>'. $crlf.
							'<feed xmlns="http://www.w3.org/2005/Atom">'. $crlf.
							'  <id>'. htmlchars($this->id). '</id>'. $crlf.
							'  <title>'. htmlchars($this->title). '</title>'. $crlf.
							'  <updated>'. date('c', $this->updated). '</updated>'. $crlf.
							'  <generator>SnowCMS (http://www.snowcms.com/)</generator>'. $crlf;

		// Well, we have part of it... Let's output!
		if(!empty($fp))
		{
			fwrite($fp, $buffer);
		}
		else
		{
			echo $buffer;
			flush();
		}

		// Empty that buffer.
		$buffer = '';

		// Authors!
		if(count($this->authors) > 0)
		{
			foreach($this->authors as $author)
			{
				$buffer .= '  <author>'. $crlf.
									 '    <name>'. htmlchars($author['name']). '</name>'. $crlf;

				if(!empty($author['email']))
				{
					$buffer .= '    <email>'. htmlchars($author['email']). '</email>'. $crlf;
				}

				if(!empty($author['uri']))
				{
					$buffer .= '    <uri>'. htmlchars($author['uri']). '</uri>'. $crlf;
				}

				$buffer .= '  </author>'. $crlf;
			}
		}

		// Looks like links!
		if(count($this->links) > 0)
		{
			foreach($this->links as $link)
			{
				$buffer .= '  <link href="'. $link['href']. '" rel="'. (empty($link['rel']) ? 'alternate' : $link['rel']). '"'. (!empty($link['type']) ? ' type="'. $link['type']. '"' : ''). (!empty($link['title']) ? ' title="'. htmlchars($link['title']). '"' : ''). (!empty($link['hreflang']) ? ' hreflang="'. $link['hreflang']. '"' : ''). (!empty($link['length']) ? ' length="'. $link['length']. '"' : ''). ' />'. $crlf;
			}
		}

		// Any categories for the feed itself?
		if(count($this->categories) > 0)
		{
			foreach($this->categories as $category)
			{
				$buffer .= '  <category term="'. htmlchars($category['term']). '"'. (!empty($category['label']) ? ' label="'. htmlchars($category['label']). '"' : ''). (!empty($category['scheme']) ? ' scheme="'. htmlchars($category['scheme']). '"' : ''). ' />'. $crlf;
			}
		}

		// Contributors? Just like authors, but not exactly the same! :P
		if(count($this->contributors) > 0)
		{
			foreach($this->contributors as $contributor)
			{
				$buffer .= '  <contributor>'. $crlf.
									 '    <name>'. htmlchars($contributor['name']). '</name>'. $crlf;

				if(!empty($contributor['email']))
				{
					$buffer .= '    <email>'. htmlchars($contributor['email']). '</email>'. $crlf;
				}

				if(!empty($contributor['uri']))
				{
					$buffer .= '    <uri>'. htmlchars($contributor['uri']). '</uri>'. $crlf;
				}

				$buffer .= '  </contributor>'. $crlf;
			}
		}

		// An icon?
		if(!empty($this->icon))
		{
			$buffer .= '  <icon>'. htmlchars($this->icon). '</icon>'. $crlf;
		}

		// A logo? (Just a bigger icon, or the icon is a small logo, whichever
		// you prefer :P)
		if(!empty($this->logo))
		{
			$buffer .= '  <logo>'. htmlchars($this->logo). '</logo>'. $crlf;
		}

		// Do you have any rights, well, do they?
		if(!empty($this->rights))
		{
			$buffer .= '  <rights>'. htmlchars($this->rights). '</rights>'. $crlf;
		}

		// And finally, a subtitle!
		if(!empty($this->subtitle))
		{
			$buffer .= '  <subtitle>'. htmlchars($this->subtitle). '</subtitle>'. $crlf;
		}

		// All done with that stuff... So save the data in the buffer.
		if(!empty($fp))
		{
			// To the stream:
			fwrite($fp, $buffer);
		}
		else
		{
			// To you!
			echo $buffer;
			flush();
		}

		// Now for the most important stuff! The entries themselves! Well, if
		// there are any.
		if(count($this->entries) > 0)
		{
			foreach($this->entries as $entry)
			{
				$buffer = '  <entry>'. $crlf;

				// There are a few required tags.
				$buffer .= '    <id>'. htmlchars($entry['id']). '</id>'. $crlf.
									 '    <title>'. htmlchars($entry['title']). '</title>'. $crlf.
									 '    <updated>'. date('c', $entry['updated']). '</updated>'. $crlf;

				// How about authors? Lots? Some? None?
				if(count($entry['authors']) > 0)
				{
					foreach($entry['authors'] as $author)
					{
						$buffer .= '    <author>'. $crlf.
											 '      <name>'. htmlchars($author['name']). '</name>'. $crlf;

						if(!empty($author['email']))
						{
							$buffer .= '      <email>'. htmlchars($author['email']). '</email>'. $crlf;
						}

						if(!empty($author['uri']))
						{
							$buffer .= '      <uri>'. htmlchars($author['uri']). '</uri>'. $crlf;
						}

						$buffer .= '    </author>'. $crlf;
					}
				}

				// How about the content of the entry?
				if(!empty($entry['content']['value']))
				{
					$buffer .= '    <content'. (!empty($entry['content']['type']) ? ' type="'. $entry['content']['type']. '"' : ' type="text"'). '>'. $entry['content']['value']. '</content>'. $crlf;
				}

				// Up next: links.
				if(count($entry['links']) > 0)
				{
					foreach($entry['links'] as $link)
					{
						$buffer .= '    <link href="'. $link['href']. '" rel="'. (empty($link['rel']) ? 'alternate' : $link['rel']). '"'. (!empty($link['type']) ? ' type="'. $link['type']. '"' : ''). (!empty($link['title']) ? ' title="'. htmlchars($link['title']). '"' : ''). (!empty($link['hreflang']) ? ' hreflang="'. $link['hreflang']. '"' : ''). (!empty($link['length']) ? ' length="'. $link['length']. '"' : ''). ' />'. $crlf;
					}
				}

				// How about the content of the entry?
				if(!empty($entry['summary']['value']))
				{
					$buffer .= '    <summary'. (!empty($entry['summary']['type']) ? ' type="'. $entry['summary']['type']. '"' : ' type="text"'). '>'. $entry['summary']['value']. '</summary>'. $crlf;
				}

				// Now categories for the entry.
				if(count($entry['categories']) > 0)
				{
					foreach($entry['categories'] as $category)
					{
						$buffer .= '    <category term="'. htmlchars($category['term']). '"'. (!empty($category['label']) ? ' label="'. htmlchars($category['label']). '"' : ''). (!empty($category['scheme']) ? ' scheme="'. htmlchars($category['scheme']). '"' : ''). ' />'. $crlf;
					}
				}

				// Ah, contributors. Don't you love them?
				if(count($entry['contibutors']))
				{
					foreach($entry['contibutors'] as $contributor)
					{
						$buffer .= '    <contributor>'. $crlf.
											 '      <name>'. htmlchars($contributor['name']). '</name>'. $crlf;

						if(!empty($contributor['email']))
						{
							$buffer .= '      <email>'. htmlchars($contributor['email']). '</email>'. $crlf;
						}

						if(!empty($contributor['uri']))
						{
							$buffer .= '      <uri>'. htmlchars($contributor['uri']). '</uri>'. $crlf;
						}

						$buffer .= '    </contributor>'. $crlf;
					}
				}

				// When was it originally published..?
				if(!empty($entry['published']))
				{
					$buffer .= '    <published>'. date('c', $entry['published']). '</published>'. $crlf;
				}

				// Some source information.
				if(!empty($entry['source']['id']) || !empty($entry['source']['title']) || !empty($entry['source']['updated']) || !empty($entry['source']['rights']))
				{
					$buffer .= '    <source>'. $crlf;

					if(!empty($entry['source']['id']))
					{
						$buffer .= '      <id>'. htmlchars($entry['source']['id']). '</id>'. $crlf;
					}

					if(!empty($entry['source']['title']))
					{
						$buffer .= '      <title>'. htmlchars($entry['source']['title']). '</title>'. $crlf;
					}

					if(!empty($entry['source']['updated']))
					{
						$buffer .= '      <updated>'. date('c', $entry['source']['updated']). '</updated>'. $crlf;
					}

					if(!empty($entry['source']['rights']))
					{
						$buffer .= '      <rights>'. htmlchars($entry['source']['rights']). '</rights>'. $crlf;
					}

					$buffer .= '    </source>'. $crlf;
				}

				// Hmm, rights?
				if(!empty($entry['rights']))
				{
					$buffer .= '    <rights>'. htmlchars($entry['rights']). '</rights>';
				}

				$buffer .= '  </entry>'. $crlf;

				// Alright, now write the buffer to, where ever!
				if(!empty($fp))
				{
					fwrite($fp, $buffer);
				}
				else
				{
					echo $buffer;
					flush();
				}
			}
		}

		// And just one, last... thing!
		if(!empty($fp))
		{
			fwrite($fp, '</entry>');

			// Free the lock too.
			flock($fp, LOCK_UN);
		}
		else
		{
			echo '</entry>';
			flush();
		}

		return true;
	}
}
?>