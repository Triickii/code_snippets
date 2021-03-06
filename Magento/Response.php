Index: lib/Zend/XmlRpc/Response.php
===================================================================
--- lib/Zend/XmlRpc/Response.php	(revision 157103)
+++ lib/Zend/XmlRpc/Response.php	(working copy)
@@ -175,12 +175,14 @@
             $this->_fault->setEncoding($this->getEncoding());
             return false;
         }
-
+        $loadEntities         = libxml_disable_entity_loader(true);
+        $useInternalXmlErrors = libxml_use_internal_errors(true);
         try {
-            $useInternalXmlErrors = libxml_use_internal_errors(true);
             $xml = new SimpleXMLElement($response);
+            libxml_disable_entity_loader($loadEntities);
             libxml_use_internal_errors($useInternalXmlErrors);
         } catch (Exception $e) {
+            libxml_disable_entity_loader($loadEntities);
             libxml_use_internal_errors($useInternalXmlErrors);
             // Not valid XML
             $this->_fault = new Zend_XmlRpc_Fault(651);
@@ -205,6 +207,7 @@
 
         try {
             if (!isset($xml->params) || !isset($xml->params->param) || !isset($xml->params->param->value)) {
+                //require_once 'Zend/XmlRpc/Value/Exception.php';
                 throw new Zend_XmlRpc_Value_Exception('Missing XML-RPC value in XML');
             }
             $valueXml = $xml->params->param->value->asXML();
Index: lib/Zend/XmlRpc/Request.php
===================================================================
--- lib/Zend/XmlRpc/Request.php	(revision 157103)
+++ lib/Zend/XmlRpc/Request.php	(working copy)
@@ -303,12 +303,15 @@
             return false;
         }
 
+        $loadEntities = libxml_disable_entity_loader(true);
         try {
             $xml = new SimpleXMLElement($request);
+            libxml_disable_entity_loader($loadEntities);
         } catch (Exception $e) {
             // Not valid XML
             $this->_fault = new Zend_XmlRpc_Fault(631);
             $this->_fault->setEncoding($this->getEncoding());
+            libxml_disable_entity_loader($loadEntities);
             return false;
         }
 