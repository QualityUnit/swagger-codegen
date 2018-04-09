package io.swagger.codegen.languages;

import com.samskivert.mustache.Mustache;
import com.samskivert.mustache.Template;

import java.io.File;
import java.io.IOException;
import java.io.StringWriter;
import java.io.Writer;
import java.util.Arrays;
import java.util.HashSet;
import java.util.Map;
import java.util.regex.Pattern;

import io.swagger.codegen.CodegenConfig;
import io.swagger.codegen.CodegenConstants;
import io.swagger.codegen.CodegenOperation;
import io.swagger.codegen.CodegenType;
import io.swagger.codegen.DefaultCodegen;
import io.swagger.codegen.SupportingFile;
import io.swagger.models.Model;
import io.swagger.models.Operation;
import io.swagger.models.Swagger;
import io.swagger.models.properties.ArrayProperty;
import io.swagger.models.properties.DecimalProperty;
import io.swagger.models.properties.Property;

@SuppressWarnings("Duplicates")
public class GwtClientCodegen extends DefaultCodegen implements CodegenConfig {

  private static class CallbackMethodLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      try {
        int code = Integer.parseInt(fragment);
        if (code >= 200 && code < 300) {
          return "onSuccess";
        }
      } catch (NumberFormatException ignore) { }
      return "error";
    }
  }

  private static class CamelizeLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return DefaultCodegen.camelize(fragment);
    }
  }

  private static class CurlyLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return "{" + fragment + "}";
    }
  }

  private static abstract class CustomLambda implements Mustache.Lambda {
    @Override
    public void execute(Template.Fragment frag, Writer out) throws IOException {
      final StringWriter tempWriter = new StringWriter();
      frag.execute(tempWriter);
      out.write(formatFragment(tempWriter.toString()));
    }

    public abstract String formatFragment(String fragment);
  }

  private static class JsArrayLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      if (fragment.endsWith("[]")) {
        return "JsArray<" + fragment.replace("[]", "") + ">";
      }
      return fragment;
    }
  }

  private static class ToWrapperLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
	  if (fragment.endsWith("[]")) {
        return "List<" + formatFragment(fragment.replace("[]", "")) + ">";
      }
      switch (fragment) {
        case "short":
          return "Short";
        case "int":
          return "Integer";
        case "boolean":
          return "Boolean";
        case "long":
          return "Long";
        case "float":
          return "Float";
        case "double":
          return "Double";
        default:
          return fragment;
      }
    }
  }

  private static class UpperCaseLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return fragment.toUpperCase();
    }
  }
  
  private static class SanitizeVariableNameLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return GwtClientCodegen.sanitizeVariableName(fragment);
    }
  }
  
  private static final String LANGUAGE_NAME = "gwt-client";

  private static final String CODEGEN_VERSION = "1.2.1";
  private static final String SUPPORT_PACKAGE = "supportPackage";

  private String supportPackage;
  protected String invokerPackage = "com.qualityunit.swagger.gwtclient";
  protected String artifactVersion = "1";

  public GwtClientCodegen() {
    super();
    outputFolder = "generated-code" + File.separator + "java";
    modelTemplateFiles.put("model.mustache", ".java");
    apiTemplateFiles.put("api.mustache", ".java");
    embeddedTemplateDir = templateDir = LANGUAGE_NAME;
    setInvokerPackage(invokerPackage);

    setReservedWordsLowerCase(Arrays.asList(
        // used as internal variables, can collide with parameter names
        "localVarPath", "localVarQueryParams", "localVarHeaderParams",
        "localVarFormParams", "localVarPostBody", "localVarAccepts",
        "localVarAccept", "localVarContentTypes", "localVarContentType",
        "localVarAuthNames", "localReturnType",

        // language reserved words
        "abstract", "continue", "for", "new", "switch", "assert", "default",
        "if", "package", "synchronized", "boolean", "do", "goto", "private",
        "this", "break", "double", "implements", "protected", "throw", "byte",
        "else", "import", "public", "throws", "case", "enum", "instanceof",
        "return", "transient", "catch", "extends", "int", "short", "try",
        "char", "final", "interface", "static", "void", "class", "finally",
        "long", "strictfp", "volatile", "const", "float", "native", "super",
        "while"));

    languageSpecificPrimitives = new HashSet<>(
        Arrays.asList("String", "boolean", "Boolean", "Double", "Integer",
            "Long", "Float", "Object", "byte[]"));
    typeMapping.put("date", "String");
    typeMapping.put("file", "Object");
    typeMapping.put("boolean", "boolean");
    typeMapping.put("string", "String");
    typeMapping.put("int", "int");
    typeMapping.put("float", "float");
    typeMapping.put("DateTime", "String");
    typeMapping.put("long", "long");
    typeMapping.put("short", "short");
    typeMapping.put("char", "String");
    typeMapping.put("double", "double");
    typeMapping.put("object", "Object");
    typeMapping.put("integer", "int");
  }

  public static String sanitizeVariableName(String name) {
    name = name.replaceAll("\\+", "PLUS");
    name = name.replaceAll("\\*", "STAR");
    name = name.replaceAll("\\?", "QUESTION_MARK");
    name = name.replaceAll("\\$", "DOLLAR");
    name = name.replaceAll("-$", "MINUS");
    name = name.replaceAll("-", "_");
    name = name.replaceAll("!", "EXCLAMATION_MARK");
    name = name.replaceAll("#", "HASH");
    name = name.replaceAll("~", "TILDE");
    name = name.replaceAll("@", "AT");
    name = name.replaceAll("%", "PERCENT");
    name = name.replaceAll("&", "AMPERSAND");
    name = name.replaceAll("=", "EQUALS");
    name = name.replaceAll(":", "COLON");
    name = name.replaceAll(";", "SEMICOLON");
    return name;
  }
  
  @Override
  public CodegenOperation fromOperation(String path, String httpMethod,
      Operation operation, Map<String, Model> definitions, Swagger swagger) {
    CodegenOperation o = super.fromOperation(path, httpMethod, operation,
        definitions, swagger);
    o.gwtphpClientMethodName = formatProcedureName(o);
    return o;
  }

  @Override
  public String getHelp() {
    return "Generates a GWT client library.";
  }

  @Override
  public String getName() {
    return LANGUAGE_NAME;
  }

  @Override
  public CodegenType getTag() {
    return CodegenType.CLIENT;
  }

  @Override
  public String getTypeDeclaration(Property p) {
    if (p instanceof ArrayProperty) {
      ArrayProperty ap = (ArrayProperty) p;
      Property inner = ap.getItems();
      return getTypeDeclaration(inner) + "[]";
    } else if (p instanceof DecimalProperty) {
      DecimalProperty dp = (DecimalProperty) p;
      String format = dp.getFormat();
      if ("float".equalsIgnoreCase(format)) {
        return "float";
      } else if ("double".equalsIgnoreCase(format)) {
        return "double";
      } else {
        return "int";
      }
    }
    return super.getTypeDeclaration(p);
  }

  @Override
  public void processOpts() {
    super.processOpts();
    if (additionalProperties.containsKey(CodegenConstants.INVOKER_PACKAGE)) {
      this.setInvokerPackage(
          (String) additionalProperties.get(CodegenConstants.INVOKER_PACKAGE));
    } else {
      additionalProperties.put(CodegenConstants.INVOKER_PACKAGE,
          invokerPackage);
    }

    if (!additionalProperties.containsKey(CodegenConstants.API_PACKAGE)) {
      additionalProperties.put(CodegenConstants.API_PACKAGE, apiPackage);
    }

    if (!additionalProperties.containsKey(CodegenConstants.MODEL_PACKAGE)) {
      additionalProperties.put(CodegenConstants.MODEL_PACKAGE, modelPackage);
    }

    if (additionalProperties.containsKey(CodegenConstants.ARTIFACT_VERSION)) {
      this.setArtifactVersion(
          (String) additionalProperties.get(CodegenConstants.ARTIFACT_VERSION));
    } else {
      additionalProperties.put(CodegenConstants.ARTIFACT_VERSION,
          artifactVersion);
    }

    if (!additionalProperties.containsKey(SUPPORT_PACKAGE)) {
      additionalProperties.put(SUPPORT_PACKAGE, supportPackage);
    } else {
      supportPackage = (String) additionalProperties.get(SUPPORT_PACKAGE);
    }

    additionalProperties.put("fnCallbackMethod", new CallbackMethodLambda());
    additionalProperties.put("fnUpperCase", new UpperCaseLambda());
    additionalProperties.put("fnJsArray", new JsArrayLambda());
    additionalProperties.put("fnCamelize", new CamelizeLambda());
    additionalProperties.put("fnCurly", new CurlyLambda());
    additionalProperties.put("fnToWrapper", new ToWrapperLambda());
    additionalProperties.put("fnSanitizeVariableName", new SanitizeVariableNameLambda());

    supportingFiles.add(new SupportingFile("ApiCallback.mustache",
        supportPackageFolder(), "ApiCallback.java"));
    supportingFiles.add(new SupportingFile("SimpleApiCallback.mustache",
        supportPackageFolder(), "SimpleApiCallback.java"));
    supportingFiles.add(new SupportingFile("LoadingIndicationApiCallback.mustache",
        supportPackageFolder(), "LoadingIndicationApiCallback.java"));
    supportingFiles.add(new SupportingFile("ApiClient.mustache",
        supportPackageFolder(), "ApiClient.java"));
    supportingFiles.add(new SupportingFile("ValidationException.mustache",
        supportPackageFolder(), "ValidationException.java"));
    supportingFiles.add(new SupportingFile("ApiClientConfig.mustache",
        supportPackageFolder(), "ApiClientConfig.java"));
    supportingFiles.add(new SupportingFile("Interceptor.mustache",
        supportPackageFolder(), "Interceptor.java"));
    supportingFiles.add(new SupportingFile("DefaultInterceptor.mustache",
        supportPackageFolder(), "DefaultInterceptor.java"));

    additionalProperties.put("codegenVersion", CODEGEN_VERSION);
  }

  public void setArtifactVersion(String artifactVersion) {
    this.artifactVersion = artifactVersion;
  }

  public void setInvokerPackage(String invokerPackage) {
    this.invokerPackage = invokerPackage;
    if (!additionalProperties.containsKey(CodegenConstants.API_PACKAGE)) {
      apiPackage = invokerPackage;
    }
    if (!additionalProperties.containsKey(CodegenConstants.MODEL_PACKAGE)) {
      modelPackage = invokerPackage + ".model";
    }
    if (!additionalProperties.containsKey(SUPPORT_PACKAGE)) {
      supportPackage = invokerPackage;
    }
  }

  private String formatProcedureName(CodegenOperation operation) {
    String result = "";
    switch (operation.httpMethod) {
      case "GET":
        result = "get";
        break;
      case "PUT":
        result = "set";
        break;
      case "DELETE":
        result = "delete";
        break;
      default:
        break;
    }

    boolean usesId = Pattern.compile("\\{.*\\}").matcher(operation.path).find();

    String operationPart = operation.path.replaceFirst("^/[^/]*/",
        "").replaceAll("\\{.*\\}", "");
    result += "/" + operationPart;

    if ("/".equals(result)) {
      result = "create";
    } else if (operationPart.isEmpty() && !usesId) {
      result += "All";
    }

    return camelize(result, true);
  }

  private String supportPackageFolder() {
    return supportPackage.replace('.', '/');
  }
}
