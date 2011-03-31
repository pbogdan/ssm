<?php

// +--------------------------------------------------------------------------+
// | tokenize.class.php                                                       |
// +--------------------------------------------------------------------------+
// | Copyright (c) 2004 Piotr Bogdan <silence@dotgeek.org>                    |
// +--------------------------------------------------------------------------+
// | Licensed under the revised BSD License;                                  |
// | you may not use this file except in compliance with the License.         |
// | You may obtain a copy of the License at:                                 |
// |   http://www.opensource.org/licenses/bsd-license.php                     |
// +--------------------------------------------------------------------------+
// | Unless required by applicable law or agreed to in writing, software      |
// | distributed under the License is distributed on an "AS IS" BASIS,        |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. |
// | See the License for the specific language governing permissions and      |
// | limitations under the License.                                           |
// +--------------------------------------------------------------------------+
// $Id: tokenize.class.php,v 1.1.1.1 2004/09/20 21:42:34 silence Exp $

/**
* This is base class for those which need to deal with tokens - 
* for example to find out what's the LIMIT given in SELECT query.
*
* All this code comes from SQLite C library - src/tokenize.c,
* version 2.8.8. And this is legal notice from that source file:
*
* The author disclaims copyright to this source code.  In place of
* a legal notice, here is a blessing:
*
*    May you do good and not evil.
*    May you find forgiveness for yourself and forgive others.
*    May you share freely, never taking more than you give.
**/

/**
* @package manager
* @subpackage parsers
*/
class tokenize {
	
	/**
	* Token codes.
	*/
	const TK_ABORT           = 1001;
	const TK_AFTER           = 1002;
	const TK_AGG_FUNCTION    = 1003;
	const TK_ALL             = 1004;
	const TK_AND             = 1005;
	const TK_AS              = 1006;
	const TK_ASC             = 1007;
	const TK_ATTACH          = 1008;
	const TK_BEFORE          = 1009;
	const TK_BEGIN           = 1010;
	const TK_BETWEEN         = 1011;
	const TK_BITAND          = 1012;
	const TK_BITNOT          = 1013;
	const TK_BITOR           = 1014;
	const TK_BY              = 1015;
	const TK_CASCADE         = 1016;
	const TK_CASE            = 1017;
	const TK_CHECK           = 1018;
	const TK_CLUSTER         = 1019;
	const TK_COLLATE         = 1020;
	const TK_COLUMN          = 1021;
	const TK_COMMA           = 1022;
	const TK_COMMENT         = 1023;
	const TK_COMMIT          = 1024;
	const TK_CONCAT          = 1025;
	const TK_CONFLICT        = 1026;
	const TK_CONSTRAINT      = 1027;
	const TK_COPY            = 1028;
	const TK_CREATE          = 1029;
	const TK_DATABASE        = 1030;
	const TK_DEFAULT         = 1031;
	const TK_DEFERRABLE      = 1032;
	const TK_DEFERRED        = 1033;
	const TK_DELETE          = 1034;
	const TK_DELIMITERS      = 1035;
	const TK_DESC            = 1036;
	const TK_DETACH          = 1037;
	const TK_DISTINCT        = 1038;
	const TK_DOT             = 1039;
	const TK_DROP            = 1040;
	const TK_EACH            = 1041;
	const TK_ELSE            = 1042;
	const TK_END             = 1043;
	const TK_END_OF_FILE     = 1044;
	const TK_EQ              = 1045;
	const TK_EXCEPT          = 1046;
	const TK_EXPLAIN         = 1047;
	const TK_FAIL            = 1048;
	const TK_FLOAT           = 1049;
	const TK_FOR             = 1050;
	const TK_FOREIGN         = 1051;
	const TK_FROM            = 1052;
	const TK_FUNCTION        = 1053;
	const TK_GE              = 1054;
	const TK_GLOB            = 1055;
	const TK_GROUP           = 1056;
	const TK_GT              = 1057;
	const TK_HAVING          = 1058;
	const TK_ID              = 1059;
	const TK_IGNORE          = 1060;
	const TK_ILLEGAL         = 1061;
	const TK_IMMEDIATE       = 1062;
	const TK_IN              = 1063;
	const TK_INDEX           = 1064;
	const TK_INITIALLY       = 1065;
	const TK_INSERT          = 1066;
	const TK_INSTEAD         = 1067;
	const TK_INTEGER         = 1068;
	const TK_INTERSECT       = 1069;
	const TK_INTO            = 1070;
	const TK_IS              = 1071;
	const TK_ISNULL          = 1072;
	const TK_JOIN            = 1073;
	const TK_JOIN_KW         = 1074;
	const TK_KEY             = 1075;
	const TK_LE              = 1076;
	const TK_LIKE            = 1077;
	const TK_LIMIT           = 1078;
	const TK_LP              = 1079;
	const TK_LSHIFT          = 1080;
	const TK_LT              = 1081;
	const TK_MATCH           = 1082;
	const TK_MINUS           = 1083;
	const TK_NE              = 1084;
	const TK_NOT             = 1085;
	const TK_NOTNULL         = 1086;
	const TK_NULL            = 1087;
	const TK_OF              = 1088;
	const TK_OFFSET          = 1089;
	const TK_ON              = 1090;
	const TK_OR              = 1091;
	const TK_ORDER           = 1092;
	const TK_PLUS            = 1093;
	const TK_PRAGMA          = 1094;
	const TK_PRIMARY         = 1095;
	const TK_RAISE           = 1096;
	const TK_REFERENCES      = 1097;
	const TK_REM             = 1098;
	const TK_REPLACE         = 1099;
	const TK_RESTRICT        = 1100;
	const TK_ROLLBACK        = 1101;
	const TK_ROW             = 1102;
	const TK_RP              = 1103;
	const TK_RSHIFT          = 1104;
	const TK_SELECT          = 1105;
	const TK_SEMI            = 1106;
	const TK_SET             = 1107;
	const TK_SLASH           = 1108;
	const TK_SPACE           = 1109;
	const TK_STAR            = 1110;
	const TK_STATEMENT       = 1111;
	const TK_STRING          = 1112;
	const TK_TABLE           = 1113;
	const TK_TEMP            = 1114;
	const TK_THEN            = 1115;
	const TK_TRANSACTION     = 1116;
	const TK_TRIGGER         = 1117;
	const TK_UMINUS          = 1118;
	const TK_UNCLOSED_STRING = 1119;
	const TK_UNION           = 1120;
	const TK_UNIQUE          = 1121;
	const TK_UPDATE          = 1122;
	const TK_UPLUS           = 1123;
	const TK_USING           = 1124;
	const TK_VACUUM          = 1125;
	const TK_VALUES          = 1126;
	const TK_VARIABLE        = 1127;
	const TK_VIEW            = 1128;
	const TK_WHEN            = 1129;
	const TK_WHERE           = 1130;
	
	/**
	* @var array
	* From SQLite src/tokenize.c:
	*
	* If X is a character that can be used in an identifier then
	* $isIdChar[X] will be 1.  Otherwise $isIdChar[X] will be 0.
	*
	* In this implementation, an identifier can be a string of
	* alphabetic characters, digits, and "_" plus any character
	* with the high-order bit set.  The latter rule means that
	* any sequence of UTF-8 characters or characters taken from
	* an extended ISO8859 character set can form an identifier.
	*/
	protected $_isIdChar = array(
		/* x0 x1 x2 x3 x4 x5 x6 x7 x8 x9 xA xB xC xD xE xF */
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,  /* 0x */
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,  /* 1x */
		0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,  /* 2x */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0,  /* 3x */
		0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* 4x */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1,  /* 5x */
		0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* 6x */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0,  /* 7x */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* 8x */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* 9x */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* Ax */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* Bx */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* Cx */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* Dx */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* Ex */
		1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,  /* Fx */
	);
	
	/**
	* @var array SQLite keyword with token codes.
	*/
	protected $_keywords = array(
		array("ABORT", tokenize::TK_ABORT ),
		array("AFTER", tokenize::TK_AFTER ),
		array("ALL", tokenize::TK_ALL ),
		array("AND", tokenize::TK_AND ),
		array("AS", tokenize::TK_AS ),
		array("ASC", tokenize::TK_ASC ),
		array("ATTACH", tokenize::TK_ATTACH ),
		array("BEFORE", tokenize::TK_BEFORE ),
		array("BEGIN", tokenize::TK_BEGIN ),
		array("BETWEEN", tokenize::TK_BETWEEN ),
		array("BY", tokenize::TK_BY ),
		array("CASCADE", tokenize::TK_CASCADE ),
		array("CASE", tokenize::TK_CASE ),
		array("CHECK", tokenize::TK_CHECK ),
		array("CLUSTER", tokenize::TK_CLUSTER ),
		array("COLLATE", tokenize::TK_COLLATE ),
		array("COMMIT", tokenize::TK_COMMIT ),
		array("CONFLICT", tokenize::TK_CONFLICT ),
		array("CONSTRAINT", tokenize::TK_CONSTRAINT ),
		array("COPY", tokenize::TK_COPY ),
		array("CREATE", tokenize::TK_CREATE ),
		array("CROSS", tokenize::TK_JOIN_KW ),
		array("DATABASE", tokenize::TK_DATABASE ),
		array("DEFAULT", tokenize::TK_DEFAULT ),
		array("DEFERRED", tokenize::TK_DEFERRED ),
		array("DEFERRABLE", tokenize::TK_DEFERRABLE ),
		array("DELETE", tokenize::TK_DELETE ),
		array("DELIMITERS", tokenize::TK_DELIMITERS ),
		array("DESC", tokenize::TK_DESC ),
		array("DETACH", tokenize::TK_DETACH ),
		array("DISTINCT", tokenize::TK_DISTINCT ),
		array("DROP", tokenize::TK_DROP ),
		array("END", tokenize::TK_END ),
		array("EACH", tokenize::TK_EACH ),
		array("ELSE", tokenize::TK_ELSE ),
		array("EXCEPT", tokenize::TK_EXCEPT ),
		array("EXPLAIN", tokenize::TK_EXPLAIN ),
		array("FAIL", tokenize::TK_FAIL ),
		array("FOR", tokenize::TK_FOR ),
		array("FOREIGN", tokenize::TK_FOREIGN ),
		array("FROM", tokenize::TK_FROM ),
		array("FULL", tokenize::TK_JOIN_KW ),
		array("GLOB", tokenize::TK_GLOB ),
		array("GROUP", tokenize::TK_GROUP ),
		array("HAVING", tokenize::TK_HAVING ),
		array("IGNORE", tokenize::TK_IGNORE ),
		array("IMMEDIATE", tokenize::TK_IMMEDIATE ),
		array("IN", tokenize::TK_IN ),
		array("INDEX", tokenize::TK_INDEX ),
		array("INITIALLY", tokenize::TK_INITIALLY ),
		array("INNER", tokenize::TK_JOIN_KW ),
		array("INSERT", tokenize::TK_INSERT ),
		array("INSTEAD", tokenize::TK_INSTEAD ),
		array("INTERSECT", tokenize::TK_INTERSECT ),
		array("INTO", tokenize::TK_INTO ),
		array("IS", tokenize::TK_IS ),
		array("ISNULL", tokenize::TK_ISNULL ),
		array("JOIN", tokenize::TK_JOIN ),
		array("KEY", tokenize::TK_KEY ),
		array("LEFT", tokenize::TK_JOIN_KW ),
		array("LIKE", tokenize::TK_LIKE ),
		array("LIMIT", tokenize::TK_LIMIT ),
		array("MATCH", tokenize::TK_MATCH ),
		array("NATURAL", tokenize::TK_JOIN_KW ),
		array("NOT", tokenize::TK_NOT ),
		array("NOTNULL", tokenize::TK_NOTNULL ),
		array("NULL", tokenize::TK_NULL ),
		array("OF", tokenize::TK_OF ),
		array("OFFSET", tokenize::TK_OFFSET ),
		array("ON", tokenize::TK_ON ),
		array("OR", tokenize::TK_OR ),
		array("ORDER", tokenize::TK_ORDER ),
		array("OUTER", tokenize::TK_JOIN_KW ),
		array("PRAGMA", tokenize::TK_PRAGMA ),
		array("PRIMARY", tokenize::TK_PRIMARY ),
		array("RAISE", tokenize::TK_RAISE ),
		array("REFERENCES", tokenize::TK_REFERENCES ),
		array("REPLACE", tokenize::TK_REPLACE ),
		array("RESTRICT", tokenize::TK_RESTRICT ),
		array("RIGHT", tokenize::TK_JOIN_KW ),
		array("ROLLBACK", tokenize::TK_ROLLBACK ),
		array("ROW", tokenize::TK_ROW ),
		array("SELECT", tokenize::TK_SELECT ),
		array("SET", tokenize::TK_SET ),
		array("STATEMENT", tokenize::TK_STATEMENT ),
		array("TABLE", tokenize::TK_TABLE ),
		array("TEMP", tokenize::TK_TEMP ),
		array("TEMPORARY", tokenize::TK_TEMP ),
		array("THEN", tokenize::TK_THEN ),
		array("TRANSACTION", tokenize::TK_TRANSACTION ),
		array("TRIGGER", tokenize::TK_TRIGGER ),
		array("UNION", tokenize::TK_UNION ),
		array("UNIQUE", tokenize::TK_UNIQUE ),
		array("UPDATE", tokenize::TK_UPDATE ),
		array("USING", tokenize::TK_USING ),
		array("VACUUM", tokenize::TK_VACUUM ),
		array("VALUES", tokenize::TK_VALUES ),
		array("VIEW", tokenize::TK_VIEW ),
		array("WHEN", tokenize::TK_WHEN ),
		array("WHERE", tokenize::TK_WHERE ),
	);
	
	/**
	* @var array Place for tokens in parsed query.
	*/
	protected $_tokens = array();
	
	/**
	* Whether parameter is white character.
	* @param char $char Character to be examined.
	* @return boolean
	*/
	private function _isspace($char) {
		
		switch($char) {
			case ' ': case "\t": case "\n": case "\f": case "\r": return true; break;
		}
		
		return false;
	}
	
	/**
	* From SQLite src/tokenize.c:
	*
	* Return the length of the token that begins at $z[0]. 
	* Store the token type in &$tokenCode before returning.
	*
	* @param string $z Query.
	* @param integer &$tokenCode Code for found token,
	* @return integer
	*/
	protected function _getToken($z, &$tokenCode) {
		$i = 0;
		
		switch($z[$i]){
			case ' ': case "\t": case "\n": case "\f": case "\r": {
				for($i = 1; $this->_isspace($z[$i]); $i++) {}
					$tokenCode = tokenize::TK_SPACE;
					return $i;
			}
			case '-': {
				if($z[1] == '-') {
					for($i = 2; $z[$i] && $z[$i]!= '\n'; $i++) {}
					$tokenCode = tokenize::TK_COMMENT;
				return $i;
				}
				$tokenCode = tokenize::TK_MINUS;
				return 1;
			}
			case '(': {
				$tokenCode = tokenize::TK_LP;
				return 1;
			}
			case ')': {
				$tokenCode = tokenize::TK_RP;
				return 1;
			}
			case ';': {
				$tokenCode = tokenize::TK_SEMI;
				return 1;
			}
			case '+': {
				$tokenCode = tokenize::TK_PLUS;
				return 1;
			}
			case '*': {
				$tokenCode = tokenize::TK_STAR;
				return 1;
			}
			case '/': {
				if($z[1] != '*' || $z[2] == 0) {
					$tokenCode = TK_SLASH;
					return 1;
				}
				for($i = 3; $z[$i] && ($z[$i]!='/' || $z[$i-1] != '*'); $i++) {}
				if($z[$i]) {
					$i++;
				}
				$tokenCode = tokenize::TK_COMMENT;
				return $i;
			}
			case '%': {
				$tokenCode = tokenize::TK_REM;
				return 1;
			}
			case '=': {
				$tokenCode = tokenize::TK_EQ;
				return 1 + ($z[1] == '=');
			}
			case '<': {
				if($z[1] == '=') {
					$tokenCode = tokenize::TK_LE;
					return 2;
				} else if($z[1] == '>') {
					$tokenCode = tokenize::TK_NE;
					return 2;
				} else if($z[1] == '<') {
					$tokenCode = tokenize::TK_LSHIFT;
					return 2;
				} else {
					$tokenCode = tokenize::TK_LT;
					return 1;
				}
			}
			case '>': {
				if($z[1] == '=') {
					$tokenCode = tokenize::TK_GE;
					return 2;
				} else if($z[1] == '>') {
					$tokenCode = tokenize::TK_RSHIFT;
					return 2;
				} else {
					$tokenCode = tokenize::TK_GT;
					return 1;
				}
			}
			case '!': {
				if($z[1] != '=') {
					$tokenCode = tokenize::TK_ILLEGAL;
					return 2;
				} else {
					$tokenCode = tokenize::TK_NE;
					return 2;
				}
			}
			case '|': {
				if($z[1] != '|') {
					$tokenCode = tokenize::TK_BITOR;
					return 1;
				} else {
					$tokenCode = tokenize::TK_CONCAT;
					return 2;
				}
			}
			case ',': {
				$tokenCode = tokenize::TK_COMMA;
				return 1;
			}
			case '&': {
				$tokenCode = tokenize::TK_BITAND;
				return 1;
			}
			case '~': {
				$tokenCode = tokenize::TK_BITNOT;
				return 1;
			}
			case '\'': case '"': {
				$delim = $z[0];
				for($i = 1; $z[$i]; $i++) {
					if($z[$i] == $delim) {
			  			if($z[i+1] == $delim) {
			    			$i++;
			  			} else {
			    			break;
			  			}
					}
				}
				if($z[$i])
					$i++;
				$tokenCode = tokenize::TK_STRING;
				return $i;
			}
			case '.': {
				$tokenCode = tokenize::TK_DOT;
				return 1;
			}
			case '0': case '1': case '2': case '3': case '4':
			case '5': case '6': case '7': case '8': case '9': {
				$tokenCode = tokenize::TK_INTEGER;
				for($i = 1; @is_numeric($z[$i]); $i++) {}
				if(@$z[@$i] == '.' && @is_numeric($z[$i+1])) {
					$i += 2;
					while(@is_numeric($z[$i]) ){ $i++; }
					$tokenCode = tokenize::TK_FLOAT;
				}
				if(($z[$i]=='e' || $z[$i]=='E') &&
			      (@is_numeric($z[i+1])
			      || (($z[$i+1]=='+' || $z[i+1]=='-') && @is_numeric($z[$i+2]))
			      )) {
					$i += 2;
					while(@is_numeric($z[$i])) {
						$i++;
					}
					$tokenCode = tokenize::TK_FLOAT;
				}
				return $i;
			}
			case '[': {
				for($i = 1; $z[$i] && $z[$i-1] != ']'; $i++) {}
					$tokenCode = tokenize::TK_ID;
				return $i;
			}
			case '?': {
				$tokenCode = tokenize::TK_VARIABLE;
				return 1;
			}
			default: {
				if(!$this->_isIdChar[ord($z[$i])]) {
					break;
				}
				for($i = 1; @$this->_isIdChar[ord($z[$i])]; $i++) {}
					$tokenCode = $this->_keywordcode(substr($z, 0, $i), $i);
				return $i;
			}
		}
		$tokenCode = tokenize::TK_ILLEGAL;
		return 1;
	}
	
	/**
	* Token code for given string.
	* @param string $string String to lookup code for.
	* @param integer $length Length of string.
	* @return integer
	*/
	protected function _keywordcode($string, $length) {
		foreach($this->_keywords as $key=>$word) {
			if(strtolower($word[0]) == strtolower($string) && strlen($word[0]) == $length) {
				return $word[1];
			}
		}
	
		return tokenize::TK_ILLEGAL;
	}
	
	/**
	* Splits given query into the tokens and stores them in $_tokens.
	* @param string $query SQL query to be parsed.
	* @return void
	*/
	protected function _tokenize($query) {
		$i = 0;
		
		while(!empty($query[$i]) || is_numeric($query[$i])) {
			$oldErrorReporting = error_reporting(0);
			$tokenLength = $this->_getToken($query, $tokenCode);
			error_reporting($oldErrorReporting);
			
			$this->_tokens[] = array(
				'tokenCode' => $tokenCode,
				'content'   => substr($query, 0, $i + $tokenLength)
			);
			
			$query = substr($query, $i + $tokenLength);
			
			if(!empty($query) || is_numeric($query)) {
				$i = 0;
			} else {
				break;
			}
		}
	}	
	/**
	* Returns string representation for specified token code.
	* @param integer $tokenCode Code of the token.
	* @return string
	*/
	protected function _tokenToString($tokenCode) {
		foreach($this->_keywords as $key=>$word) {
			if($word[1] == $code) {
				return $word[0];
			}
		}
		
		return "TK_ILLEGAL";
	}
	
	/**
	* Looks for token in $_tokens array. Returns array index.
	* @param integer $tokenCode Code of the token to find.
	* @return integer
	*/
	protected function _tokenGetPos($tokenCode) {
		
		for($i = 0; $i < sizeof($this->_tokens); $i++) {
			if($this->_tokens[$i]['tokenCode'] == $tokenCode) {
				return $i;
			}
		}
		
		return -1;
	}
	
	/**
	* Replaces token at position $position in $_tokens array.
	* @param integer $position Position to be replaced.
	* @param array $newtoken Token to be inserted.
	* @return void
	*/
	protected function _replaceToken($position, $newtoken) {
		try{
			if(!is_numeric($position) || !is_array($newtoken)) {
				throw new Exception('$position must be numeric, $newtoken must be array');
			}
		} catch(Exception $e) {
			$e->getMessage();
		}
		$this->_tokens[$position] = $newtoken;
	}
	
	/**
	* Inserts new token at given position.
	* @param integer $position Position to insert at.
	* @param array $token Token to be inserted.
	* @return void
	*/
	protected function _insertToken($position, $token) {
		switch($position) {
			case 0: array_unshift($this->_tokens, $token); break;
			case (sizeof($this->_tokens)): $this->_tokens[] = $token; break;
			default: {
				$tmp = array();
				$i = 1;
				while($tk = array_shift($this->_tokens)) {
					$tmp[] = $tk;
					if($i  == $position) {
						$tmp[] = $token;
					}
					$i++;
				}
				$this->_tokens = $tmp;
			}
		}
	}
}

?>
