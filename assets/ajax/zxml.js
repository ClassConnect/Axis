/*------------------------------------------------------------------------------
 * JavaScript zXml Library
 * Version 1.0
 * by Nicholas C. Zakas, http://www.nczonline.net/
 * Copyright (c) 2004-2005 Nicholas C. Zakas. All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 *------------------------------------------------------------------------------
 */  

/**
 * Legacy zXml settings for backwards compatibility.
 */
var zXml = {
    useActiveX /*:Boolean*/ : (typeof ActiveXObject != "undefined"),
    useDom /*:Boolean*/: document.implementation && document.implementation.createDocument,
    useXmlHttp /*:Boolean*/: (typeof XMLHttpRequest != "undefined"),
    
    settings : {

        /**
		 * Does this browser support native XMLHttpRequest?
		 */
		hasXmlHttp /*:Boolean*/: (typeof XMLHttpRequest != "undefined"),
		
		/**
		 * Does this browser support ActiveX controls?
		 */
		hasActiveX /*:Boolean*/: (typeof ActiveXObject != "undefined"),
		
		/**
		 * Does this browser support native DOM creation?
		 */
		hasXmlDom /*:Boolean*/: (document.implementation && document.implementation.hasFeature("XML", "1.0")),
		
		/**
		 * Does this browser support DOM LoadSave?
		 */
		hasDomLS /*:Boolean*/: (document.implementation && document.implementation.hasFeature("LS", "3.0")),
		
		/**
		 * Does this browser support DOM LoadSave?
		 */
		hasDomLSAsync /*:Boolean*/: (document.implementation && document.implementation.hasFeature("LS-Async", "3.0")),
		
		/**
		 * Does this browser support a native DOMParser?
		 */
		hasDomParser /*:Boolean*/: (typeof DOMParser != "undefined"),
		
		/**
		 * Does this browser support a native XMLSerializer?
		 */
		hasXmlSerializer /*:Boolean*/: (typeof XMLSerializer != "undefined"),
		
		/**
		 * Does this browser have an XSLTProcessor?
		 */
		hasXSLTProcessor /*:Boolean*/: (typeof XSLTProcessor != "undefined")
    }
};

zXml.ARR_XMLHTTP_VERS = ["MSXML2.XmlHttp.6.0", "MSXML2.XmlHttp.3.0"];

zXml.ARR_DOM_VERS = ["MSXML2.DOMDocument.6.0", "MSXML2.DOMDocument.3.0"];
 
/**
 * Static class for handling XMLHttp creation.
 * @class
 * @scope public
 */                     
function zXmlHttp() {
}

/**
 * Creates an XMLHttp object.
 * @static
 * @scope public
 * @return An XMLHttp object.
 */
zXmlHttp.createRequest = function ()/*:XMLHttp*/ {

    if (zXml.settings.hasXmlHttp) {
        return new XMLHttpRequest();
    } else if (zXml.settings.hasActiveX) {
        if (!zXml.XMLHTTP_VER) {
            for (var i=0; i < zXml.ARR_XMLHTTP_VERS.length; i++) {
                try {
                    new ActiveXObject(zXml.ARR_XMLHTTP_VERS[i]);
                    zXml.XMLHTTP_VER = zXml.ARR_XMLHTTP_VERS[i];
                    break;
                } catch (oError) {                
                }
            }
        }
        
        if (zXml.XMLHTTP_VER) {
            return new ActiveXObject(zXml.XMLHTTP_VER);
        } else {
            throw new Error("Could not create XML HTTP Request.");
        }
    } else {
        throw new Error("Your browser doesn't support an XML HTTP Request.");
    }
};

/**
 * Indicates if XMLHttp is available.
 * @static
 * @scope public
 * @return True if XMLHttp is available, false if not.
 */
zXmlHttp.isSupported = function ()/*:Boolean*/ {
    return zXml.settings.hasXmlHttp || zXml.settings.hasActiveX;
};


/**
 * Static class for handling XML DOM creation.
 * @class
 * @scope public
 */
function zXmlDom() {

}

/**
 * Creates an XML DOM document.
 * @static
 * @scope public
 * @return An XML DOM document.
 */
zXmlDom.createDocument = function () /*:XMLDocument*/{

    //implements createDocument()
    if (zXml.settings.hasXmlDom) {

		    //create the document
        var oXmlDom = document.implementation.createDocument("","",null);

				//simulate parse error object
        oXmlDom.parseError = {
            valueOf: function () { return this.errorCode; },
            toString: function () { return this.errorCode.toString() }
        };
        
				//initialize the error object
        oXmlDom.__initError__();
                
				//add an event listener for load								
        oXmlDom.addEventListener("load", function () {
            this.__checkForErrors__();
            this.__changeReadyState__(4);
        }, false);

				//return the object
        return oXmlDom;        
        
    } else if (zXml.settings.hasActiveX) {
        if (!zXml.DOM_VER) {
            for (var i=0; i < zXml.ARR_DOM_VERS.length; i++) {
                try {
                    new ActiveXObject(zXml.ARR_DOM_VERS[i]);
                    zXml.DOM_VER = zXml.ARR_DOM_VERS[i];
                    break;
                } catch (oError) {                
                }
            }
        }
        
        if (zXml.DOM_VER) {
            return new ActiveXObject(zXml.DOM_VER);
        } else {
            throw new Error("Could not create XML DOM document.");
        }
    } else {
        throw new Error("Your browser doesn't support an XML DOM document.");
    }

};

/**
 * Indicates if an XML DOM is available.
 * @static
 * @scope public
 * @return True if XML DOM is available, false if not.
 */
zXmlDom.isSupported = function ()/*:Boolean*/ {
    return zXml.settings.hasXmlDom || zXml.settings.hasActiveX;
};

//Get a reference to the XMLDocument or Document class (Mozilla and Opera)
var oDomDocument = null;
if (typeof XMLDocument != "undefined") {
    oDomDocument = XMLDocument;
} else if (typeof Document != "undefined") {
    oDomDocument = Document;
}

//This block of code is used by both Opera and Mozilla. Perhaps
//Safari will use it as well.
if (oDomDocument) {

    //create a ready state for the document
    oDomDocument.prototype.readyState = 0;
		
		//setup an empty event handler
    oDomDocument.prototype.onreadystatechange = null;

		//function to fire whenever the readyState changes
    oDomDocument.prototype.__changeReadyState__ = function (iReadyState) {
        this.readyState = iReadyState;

        if (typeof this.onreadystatechange == "function") {
            this.onreadystatechange();
        }
    };

		//initialize the error object
    oDomDocument.prototype.__initError__ = function () {
        this.parseError.errorCode = 0;
        this.parseError.filepos = -1;
        this.parseError.line = -1;
        this.parseError.linepos = -1;
        this.parseError.reason = null;
        this.parseError.srcText = null;
        this.parseError.url = null;
    };
    
    //function load the DOM of another document
    oDomDocument.prototype.__loadDom__ = function (oXmlDom) {
        while (this.firstChild) {
            this.removeChild(this.firstChild);
        }

        for (var i=0; i < oXmlDom.childNodes.length; i++) {
            var oNewNode = this.importNode(oXmlDom.childNodes[i], true);
            this.appendChild(oNewNode);
        }
    };
		
		//determine if the async property is needed
		try {
		    if (typeof oDomDocument.prototype.async != "boolean") {
		        oDomDocument.prototype.async = true;
		    }
		} catch (e) {}
		
		//overwrite the load() method
		//alert(oDomDocument.prototype.load);
		oDomDocument.prototype.load = function (sURL) {
		
		    this.__initError__();
				
			//use XMLHttp to mimic the correct functionality
			var oHttp = zXmlHttp.createRequest();
			var oDom = this;
			oHttp.open("get", sURL, this.async);
			if (this.async) {
			    oHttp.onreadystatechange = function () {
				    if (oHttp.readyState == 4) {
				        oHttp.onreadystatechange = null;
						    oDom.__loadDom__(oHttp.responseXML);
						    oDom.__checkForErrors__();
				    }
				    
				    oDom.__changeReadyState__(oHttp.readyState);
			    };
			}
			
			oHttp.send(null);
			
			if (!this.async) oDom.__loadDom__(oHttp.responseXML);	
		};
		
		Node.prototype.getText = function () {
		    var sText = "";
            for (var i = 0; i < this.childNodes.length; i++) {
                if (this.childNodes[i].hasChildNodes()) {
                    sText += this.getText();
                } else {
                    sText += this.childNodes[i].nodeValue;
                }
            }
            return sText;
		};
		
		Node.prototype.getXml = function () {
            return (new XMLSerializer()).serializeToString(this, "text/xml") || 
                (new XMLSerializer()).serializeToString(this);
		};
		
		//determine if the browser supports Dom LoadSave
		if (zXml.settings.hasDomLS) {
		
		    //way to handle errors using DOM LS that mimicks IE's parseError object
            oDomDocument.prototype.__checkForErrors__ = function (oError) {
                if (!oError) return;
                
                this.parseError.errorCode = -999999;
                this.parseError.reason = oError.message;
                this.parseError.url = oError.location.uri;
                this.parseError.line = oError.location.lineNumber;
                this.parseError.linepos = oError.location.columnNumber;
                this.parseError.srcText = (oError.location.relatedNode)?"Around " + oError.location.relatedNode.nodeName:oError.type;
            };		
				
		    //loadXML() implementation by Jeremy McPeak
            oDomDocument.prototype.loadXML = function (sXml) {
                this.__initError__();
                this.__changeReadyState__(1);
                
                var oDom = this;
                var iMode = document.implementation.MODE_SYNCHRONOUS;
                
                //Create the parser in synchronous mode.
                var oParser = document.implementation.createLSParser(iMode, null);
    						
                //Assign the error handler
                oParser.domConfig.setParameter("error-handler", 
                    function (oEx) {
                        oDom.__checkForErrors__(oEx);
                        oDom.__changeReadyState__(4);
                    }
                );
                //The LoadSave interface requires an LSInput object to load with parse().
                //The stringData property is used.
                var oInput = document.implementation.createLSInput();
                oInput.stringData = sXml;
                
                //Call parse() and send the resulting DOM to __loadDom__ to load it
                //into this document.
                try {
                    var oXmlDom = oParser.parse(oInput);
                    this.__loadDom__(oXmlDom);
                    this.__changeReadyState__(4);
                } catch (e) {} //We don't want to do anything here. LSException objects suck for info.
            };				
		} else  {
		
		    //sorry opera, 
		    if (!window.opera) {
				    //mozilla-specific way of handling errors
            oDomDocument.prototype.__checkForErrors__ = function (oEx) {
        
                if (this.documentElement.tagName == "parsererror") {
        
                    var reError = />([\s\S]*?)Location:([\s\S]*?)Line Number (\d+), Column (\d+):<sourcetext>([\s\S]*?)(?:\-*\^)/;
        
                    reError.test(this.xml);
                    
                    this.parseError.errorCode = -999999;
                    this.parseError.reason = RegExp.$1;
                    this.parseError.url = RegExp.$2;
                    this.parseError.line = parseInt(RegExp.$3);
                    this.parseError.linepos = parseInt(RegExp.$4);
                    this.parseError.srcText = RegExp.$5;
                }
            };				
		}
				
				//implementation of loadXML for DOMParser browsers
        oDomDocument.prototype.loadXML = function (sXml) {
        
            this.__initError__();
        
            this.__changeReadyState__(1);
        
            var oParser = new DOMParser();
            var oXmlDom = null;
						var bErrorChecked = false;
						
						//some browsers throw an error if parsing fails
						try {
    						oXmlDom = oParser.parseFromString(sXml, "text/xml");                
                this.__loadDom__(oXmlDom);
						} catch (oEx) {
						    this.__checkForErrors__(oEx);
								bErrorChecked = true;
						}
            
						if (!bErrorChecked) {
                this.__checkForErrors__();
					  }
            
            this.__changeReadyState__(4);
        };				
		
		}
		
		//add properties if supported
		if (Node.prototype.__defineGetter__) {
		
		    //define xml property if not already defined
		    if (typeof Node.prototype.xml == "undefined") {
            Node.prototype.__defineGetter__("xml", function () {
                return this.getXml();
            });
        }
    
		    //add text property if not already defined
		    if (typeof Node.prototype.text == "undefined") {		
                Node.prototype.__defineGetter__("text", function () {
                    return this.getText();
                });
			}
		}
}

/**
 * Static class for handling XSLT transformations.
 * @class
 * @scope public
 */
function zXslt() {
}

/**
 * Transforms an XML DOM to text using an XSLT DOM.
 * @static
 * @scope public
 * @param oXml The XML DOM to transform.
 * @param oXslt The XSLT DOM to use for the transformation.
 * @return The transformed version of the string.
 */
zXslt.transformToText = function (oXml /*:XMLDocument*/, oXslt /*:XMLDocument*/)/*:String*/ {
    if (zXml.settings.hasXSLTProcessor) {
        var oProcessor = new XSLTProcessor();
        oProcessor.importStylesheet(oXslt);
    
        var oResultDom = oProcessor.transformToDocument(oXml);
        var sResult = oResultDom.getXml();
        
        if (sResult.indexOf("<transformiix:result") > -1) {
            sResult = sResult.substring(sResult.indexOf(">") + 1, 
                                        sResult.lastIndexOf("<"));
        }
    
        return sResult;     
    } else if (zXml.settings.hasActiveX) {
        return oXml.transformNode(oXslt);
    } else {
        throw new Error("No XSLT engine found.");
    }
};

/**
 * Static class for handling XPath evaluation.
 * @class
 * @scope public
 */
function zXPath() {

}

/**
 * Selects the first node matching a given XPath expression.
 * @static
 * @scope public
 * @param oRefNode The node from which to evaluate the expression.
 * @param sXPath The XPath expression.
 * @param oXmlNs An object containing the namespaces used in the expression. Optional.
 * @return An XML node matching the expression or null if no matches found.
 */
zXPath.selectNodes = function (oRefNode /*:Node*/, sXPath /*:String*/, oXmlNs /*:Object*/) {
    if (oRefNode.ownerDocument && oRefNode.ownerDocument.evaluate) {
    
        oXmlNs = oXmlNs || {};
        
        var nsResolver = function (sPrefix) {
    			  return oXmlNs[sPrefix];
        };
		
        var oResult = oRefNode.ownerDocument.evaluate(sXPath, oRefNode, nsResolver, 
                                          XPathResult.ORDERED_NODE_ITERATOR_TYPE, 
                                          null);

        var aNodes = new Array;
        
        if (oResult != null) {
            var oElement = oResult.iterateNext();
            while(oElement) {
                aNodes.push(oElement);
                oElement = oResult.iterateNext();
            }
        }
        
        return aNodes;
        
    } else if (zXml.settings.hasActiveX) {
        if (oXmlNs) {
            var sXmlNs = "";
            for (var sProp in oXmlNs) {
                if (oXmlNs.hasOwnProperty(sProp)) {
                    sXmlNs += "xmlns:" + sProp + "=\'" + oXmlNs[sProp] + "\' ";
                }
            }
            oRefNode.ownerDocument.setProperty("SelectionNamespaces", sXmlNs);
    	}  		
        return oRefNode.selectNodes(sXPath);
    } else {
        throw new Error("No XPath engine found.");
    }

};

/**
 * Selects the first node matching a given XPath expression.
 * @static
 * @scope public
 * @param oRefNode The node from which to evaluate the expression.
 * @param sXPath The XPath expression.
 * @param oXmlNs An object containing the namespaces used in the expression.
 * @return An XML node matching the expression or null if no matches found.
 */
zXPath.selectSingleNode = function (oRefNode /*:Node*/, sXPath /*:String*/, oXmlNs /*:Object*/) {
    if (oRefNode.ownerDocument && oRefNode.ownerDocument.evaluate) {            
	
        oXmlNs = oXmlNs || {};
        
        var nsResolver = function (sPrefix) {
            return oXmlNs[sPrefix];
        };
    
        var oResult = oRefNode.ownerDocument.evaluate(sXPath, oRefNode, nsResolver,
                                          XPathResult.FIRST_ORDERED_NODE_TYPE, null);
    
        if (oResult != null) {
            return oResult.singleNodeValue;
        } else {
            return null;
        }
    
    } else if (zXml.settings.hasActiveX) {
        if (oXmlNs) {
            var sXmlNs = "";
            for (var sProp in oXmlNs) {
                if (oXmlNs.hasOwnProperty(sProp)) {
                    sXmlNs += "xmlns:" + sProp + "=\'" + oXmlNs[sProp] + "\' ";
                }
            }
            oRefNode.ownerDocument.setProperty("SelectionNamespaces", sXmlNs);
    	}    
        return oRefNode.selectSingleNode(sXPath);
    } else {
        throw new Error("No XPath engine found.")
    }

};

/**
 * General purpose XML serializer.
 * @class
 */
function zXMLSerializer() {

}

/**
 * Serializes the given XML node into an XML string.
 * @param oNode The XML node to serialize.
 * @return An XML string.
 */
zXMLSerializer.prototype.serializeToString = function (oNode /*:Node*/)/*:String*/ {

    var sXml = "";
    
    switch (oNode.nodeType) {
        case 1: //element
            sXml = "<" + oNode.tagName;
            
            for (var i=0; i < oNode.attributes.length; i++) {
                sXml += " " + oNode.attributes[i].name + "=\"" + oNode.attributes[i].value + "\"";
            }
            
            sXml += ">";
            
            for (var i=0; i < oNode.childNodes.length; i++){
                sXml += this.serializeToString(oNode.childNodes[i]);
            }
            
            sXml += "</" + oNode.tagName + ">";
            break;
            
        case 3: //text node
            sXml = oNode.nodeValue;
            break;
        case 4: //cdata
            sXml = "<![CDATA[" + oNode.nodeValue + "]]>";
            break;
        case 7: //processing instruction
            sXml = "<?" + oNode.nodevalue + "?>";
            break;
        case 8: //comment
            sXml = "<!--" + oNode.nodevalue + "-->";
            break;
        case 9: //document
            for (var i=0; i < oNode.childNodes.length; i++){
                sXml += this.serializeToString(oNode.childNodes[i]);
            }
            break;
            
    }  
    
    return sXml;
};