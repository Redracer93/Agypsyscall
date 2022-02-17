<?php

/*
 * Note: Spin Rewriter API server is using a 120-second timeout.
 * Client scripts should use a 150-second timeout to allow for HTTP connection overhead.
 */
set_time_limit(150);

class SpinRewriterAPI {

	var $data;
	var $response;
	var $api_url;

	/**
	 * Spin Rewriter API constructor, complete with authentication.
	 * @param $email_address
	 * @param $api_key
	 */
	function __construct($email_address, $api_key) {
		$this->data = array();
		$this->data['email_address'] = $email_address;
		$this->data['api_key'] = $api_key;
		$this->api_url = "http://www.spinrewriter.com/action/api";
	}

	/**
	 * Returns the API Quota (the number of made and remaining API calls for the 24-hour period).
	 */
	function getQuota() {
		$this->data['action'] = "api_quota";
		$this->makeRequest();
		return $this->parseResponse();
	}

	/**
	 * Returns the processed text with the {first option|second option} spinning syntax.
	 * @param $text
	 * @return String
	 */
	function getTextWithSpintax($text) {
		$this->data['action'] = "text_with_spintax";
		$this->data['text'] = $text;
		$this->makeRequest();
		return $this->parseResponse();
	}

	/**
	 * Returns one of the possible unique variations of the processed text.
	 * @param $text
	 * @return String
	 */
	function getUniqueVariation($text) {
		$this->data['action'] = "unique_variation";
		$this->data['text'] = $text;
		$this->makeRequest();
		return $this->parseResponse();
	}

	/**
	 * Returns one of the possible unique variations of given text that already contains valid spintax. No additional processing is done.
	 * @param $text
	 * @return String
	 */
	function getUniqueVariationFromSpintax($text) {
		$this->data['action'] = "unique_variation_from_spintax";
		$this->data['text'] = $text;
		$this->makeRequest();
		return $this->parseResponse();
	}

	/**
	 * Sets the list of protected keywords and key phrases.
	 * @param $protected_terms (array of words, comma separated list, newline separated list)
	 * @return boolean
	 */
	function setProtectedTerms($protected_terms) {
		$this->data['protected_terms'] = "";
		if (is_array($protected_terms)) {
			// array of words
			foreach ($protected_terms as $protected_term) {
				$protected_term = trim($protected_term);
				if (is_string($protected_term) && strlen($protected_term) > 2) {
					$this->data['protected_terms'] .= $protected_term . "\n";
				}
			}
			$this->data['protected_terms'] = trim($this->data['protected_terms']);
			return true;
		} else if (strpos($protected_terms, ",") !== false) {
			// comma separated list
			$protected_terms_explode = explode(",", $protected_terms);
			foreach ($protected_terms_explode as $protected_term) {
				$protected_term = trim($protected_term);
				if ($protected_term && strlen($protected_term) > 2) {
					$this->data['protected_terms'] .= $protected_term . "\n";
				}
			}
			$this->data['protected_terms'] = trim($this->data['protected_terms']);
			return true;
		} else if (strpos(trim($protected_terms), "\n") !== false) {
			// newline separated list (the officially supported format)
			$protected_terms = trim($protected_terms);
			if (strlen($protected_terms) > 0) {
				$this->data['protected_terms'] = $protected_terms;
				return true;
			} else {
				return false;
			}
		} else if (is_string(trim($protected_terms)) && strlen(trim($protected_terms)) > 2 && strlen(trim($protected_terms)) < 50) {
			// a single word or phrase (the officially supported format)
			$this->data['protected_terms'] = trim($protected_terms);
			return true;
		} else {
			// invalid format
			return false;
		}
	}

	/**
	 * Sets whether the One-Click Rewrite process automatically protects Capitalized Words outside the article's title.
	 * @param $auto_protected_terms boolean
	 * @return boolean
	 */
	function setAutoProtectedTerms($auto_protected_terms) {
		if ($auto_protected_terms == "true" || $auto_protected_terms === true || intval($auto_protected_terms) == 1) {
			$auto_protected_terms = "true";
		} else {
			$auto_protected_terms = "false";
		}
		$this->data['auto_protected_terms'] = $auto_protected_terms;
		return true;
	}

	/**
	 * Sets the confidence level of the One-Click Rewrite process.
	 * @param $confidence_level ('low', 'medium', 'high')
	 * @return boolean
	 */
	function setConfidenceLevel($confidence_level) {
		$this->data['confidence_level'] = $confidence_level;
		return true;
	}

	/**
	 * Sets whether the One-Click Rewrite process uses nested spinning syntax (multi-level spinning) or not.
	 * @param $nested_spintax boolean
	 * @return boolean
	 */
	function setNestedSpintax($nested_spintax) {
		if ($nested_spintax == "true" || $nested_spintax === true || intval($nested_spintax) == 1) {
			$nested_spintax = "true";
		} else {
			$nested_spintax = "false";
		}
		$this->data['nested_spintax'] = $nested_spintax;
		return true;
	}

	/**
	 * Sets whether Spin Rewriter rewrites complete sentences on its own.
	 * @param $auto_sentences boolean
	 * @return boolean
	 */
	function setAutoSentences($auto_sentences) {
		if ($auto_sentences == "true" || $auto_sentences === true || intval($auto_sentences) == 1) {
			$auto_sentences = "true";
		} else {
			$auto_sentences = "false";
		}
		$this->data['auto_sentences'] = $auto_sentences;
		return true;
	}

	/**
	 * Sets whether Spin Rewriter rewrites entire paragraphs on its own.
	 * @param $auto_paragraphs boolean
	 * @return boolean
	 */
	function setAutoParagraphs($auto_paragraphs) {
		if ($auto_paragraphs == "true" || $auto_paragraphs === true || intval($auto_paragraphs) == 1) {
			$auto_paragraphs = "true";
		} else {
			$auto_paragraphs = "false";
		}
		$this->data['auto_paragraphs'] = $auto_paragraphs;
		return true;
	}

	/**
	 * Sets whether Spin Rewriter writes additional paragraphs on its own.
	 * @param $auto_new_paragraphs boolean
	 * @return boolean
	 */
	function setAutoNewParagraphs($auto_new_paragraphs) {
		if ($auto_new_paragraphs == "true" || $auto_new_paragraphs === true || intval($auto_new_paragraphs) == 1) {
			$auto_new_paragraphs = "true";
		} else {
			$auto_new_paragraphs = "false";
		}
		$this->data['auto_new_paragraphs'] = $auto_new_paragraphs;
		return true;
	}

	/**
	 * Sets whether Spin Rewriter changes the entire structure of phrases and sentences.
	 * @param $auto_sentence_trees boolean
	 * @return boolean
	 */
	function setAutoSentenceTrees($auto_sentence_trees) {
		if ($auto_sentence_trees == "true" || $auto_sentence_trees === true || intval($auto_sentence_trees) == 1) {
			$auto_sentence_trees = "true";
		} else {
			$auto_sentence_trees = "false";
		}
		$this->data['auto_sentence_trees'] = $auto_sentence_trees;
		return true;
	}

	/**
	 * Sets whether Spin Rewriter should only use synonyms (where available) when generating spun text.
	 * @param $use_only_synonyms boolean
	 * @return boolean
	 */
	function setUseOnlySynonyms($use_only_synonyms) {
		if ($use_only_synonyms == "true" || $use_only_synonyms === true || intval($use_only_synonyms) == 1) {
			$use_only_synonyms = "true";
		} else {
			$use_only_synonyms = "false";
		}
		$this->data['use_only_synonyms'] = $use_only_synonyms;
		return true;
	}

	/**
	 * Sets whether Spin Rewriter should intelligently randomize the order of paragraphs and lists when generating spun text.
	 * @param $reorder_paragraphs boolean
	 * @return boolean
	 */
	function setReorderParagraphs($reorder_paragraphs) {
		if ($reorder_paragraphs == "true" || $reorder_paragraphs === true || intval($reorder_paragraphs) == 1) {
			$reorder_paragraphs = "true";
		} else {
			$reorder_paragraphs = "false";
		}
		$this->data['reorder_paragraphs'] = $reorder_paragraphs;
		return true;
	}

	/**
	 * Sets the desired spintax format to be used with the returned spun text.
	 * @param $spintax_format ('{|}', '{~}', '[|]', '[spin]')
	 * @return boolean
	 */
	function setSpintaxFormat($spintax_format) {
		$this->data['spintax_format'] = $spintax_format;
		return true;
	}

	/**
	 * Parses raw JSON response and returns a native PHP array.
	 * @return String
	 */
	private function parseResponse() {
		return json_decode($this->response, true);
	}

	/**
	 * Sends a request to the Spin Rewriter API and saves the unformatted response.
	 */
	private function makeRequest() {
		$data_raw = "";
		foreach ($this->data as $key => $value){
			$data_raw = $data_raw . $key . "=" . urlencode($value) . "&";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_raw);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$this->response = trim(curl_exec($ch));
		curl_close($ch);
	}
}

?>