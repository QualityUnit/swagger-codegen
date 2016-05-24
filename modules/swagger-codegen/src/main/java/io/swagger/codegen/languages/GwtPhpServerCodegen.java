package io.swagger.codegen.languages;

import com.samskivert.mustache.Mustache;
import com.samskivert.mustache.Template;

import org.apache.commons.lang3.StringUtils;

import java.io.File;
import java.io.IOException;
import java.io.StringWriter;
import java.io.Writer;
import java.util.Arrays;
import java.util.HashMap;
import java.util.HashSet;

import io.swagger.codegen.CodegenConfig;
import io.swagger.codegen.CodegenConstants;
import io.swagger.codegen.CodegenProperty;
import io.swagger.codegen.CodegenType;
import io.swagger.codegen.DefaultCodegen;
import io.swagger.codegen.SupportingFile;
import io.swagger.models.properties.ArrayProperty;
import io.swagger.models.properties.MapProperty;
import io.swagger.models.properties.Property;
import io.swagger.models.properties.RefProperty;
import io.swagger.models.properties.StringProperty;

@SuppressWarnings("Duplicates")
public class GwtPhpServerCodegen extends DefaultCodegen
    implements CodegenConfig {
  private static class ClassNameLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      String[] parts = fragment.split("::", 2);
      if (parts.length != 2) {
        return "UNKNOWN";
      }
      return parts[0];
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
  private static class ItemTypeLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return fragment.replace("[", "").replace("]", "");
    }
  }
  private static class MethodNameLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      String[] parts = fragment.split("::", 2);
      if (parts.length != 2) {
        return "UNKNOWN";
      }
      return parts[1];
    }
  }
  private static class SlimPathLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return fragment.replace("{", ":").replace("}", "");
    }
  }

  private static class UpperCaseLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return fragment.toUpperCase();
    }
  }

  private static class LowerCaseLambda extends CustomLambda {
    @Override
    public String formatFragment(String fragment) {
      return fragment.toLowerCase();
    }
  }

  private static final String PACKAGE_PATH = "packagePath";
  private static final String SRC_BASE_PATH = "srcBasePath";
  private static final String CODEGEN_VERSION = "1.3.0";
  private static final String LANGUAGE_NAME = "gwtphp";
  private static final String TEMPLATE_DIR = "gwtphp";

  private String invokerPackage = "GwtPhp";
  private String packagePath = "";
  private String artifactVersion = "1";
  private String srcBasePath = "include/" + invokerPackage;

  public GwtPhpServerCodegen() {
    super();

    outputFolder = "generated-code" + File.separator + "php";
    modelTemplateFiles.put("model.mustache", ".class.php");
    embeddedTemplateDir = templateDir = TEMPLATE_DIR;
    setInvokerPackage(invokerPackage);

    // noinspection SpellCheckingInspection
    reservedWords = new HashSet<>(Arrays.asList("__halt_compiler", "abstract",
        "and", "array", "as", "break", "callable", "case", "catch", "class",
        "clone", "const", "continue", "declare", "default", "die", "do", "echo",
        "else", "elseif", "empty", "enddeclare", "endfor", "endforeach",
        "endif", "endswitch", "endwhile", "eval", "exit", "extends", "final",
        "for", "foreach", "function", "global", "goto", "if", "implements",
        "include", "include_once", "instanceof", "insteadof", "interface",
        "isset", "list", "namespace", "new", "or", "print", "private",
        "protected", "public", "require", "require_once", "return", "static",
        "switch", "throw", "trait", "try", "unset", "use", "var", "while",
        "xor"));

    // ref: http://php.net/manual/en/language.types.intro.php
    languageSpecificPrimitives = new HashSet<>(Arrays.asList("bool", "boolean",
        "int", "integer", "double", "float", "string", "object", "DateTime",
        "mixed", "number", "void", "byte", "Number", "Integer"));

    instantiationTypes.put("array", "array");
    instantiationTypes.put("map", "map");

    // provide primitives to mustache template
    String primitives = "'"
        + StringUtils.join(languageSpecificPrimitives, "', '") + "'";
    additionalProperties.put("primitives", primitives);

    // ref:
    // https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#data-types
    typeMapping = new HashMap<>();
    typeMapping.put("integer", "int");
    typeMapping.put("number", "float");
    typeMapping.put("long", "int");
    typeMapping.put("float", "float");
    typeMapping.put("double", "double");
    typeMapping.put("string", "string");
    typeMapping.put("byte", "int");
    typeMapping.put("boolean", "bool");
    typeMapping.put("date", "string");
    typeMapping.put("datetime", "string");
    typeMapping.put("file", "file");
    typeMapping.put("map", "map");
    typeMapping.put("array", "array");
    typeMapping.put("list", "array");
    typeMapping.put("object", "object");
    typeMapping.put("DateTime", "string");
  }

  @Override
  public String apiFileFolder() {
    return (outputFolder + "/" + toPackagePath(apiPackage(), srcBasePath));
  }

  @Override
  public String escapeReservedWord(String name) {
    return "_" + name;
  }

  @Override
  public CodegenProperty fromProperty(final String name, final Property p) {
    CodegenProperty cp = super.fromProperty(name, p);
    cp.hasDefaultValue = !"null".equals(cp.defaultValue);
    return cp;
  }

  @Override
  public String getHelp() {
    return "Generates a PHP server library.";
  }

  @Override
  public String getName() {
    return LANGUAGE_NAME;
  }

  @Override
  public String getSwaggerType(Property p) {
    String swaggerType = super.getSwaggerType(p);
    String type;
    if (typeMapping.containsKey(swaggerType)) {
      type = typeMapping.get(swaggerType);
      if (languageSpecificPrimitives.contains(type)) {
        return type;
      } else if (instantiationTypes.containsKey(type)) {
        return type;
      }
    } else {
      type = swaggerType;
    }
    if (type == null) {
      return null;
    }
    return toModelName(type);
  }

  @Override
  public CodegenType getTag() {
    return CodegenType.SERVER;
  }

  @Override
  public String getTypeDeclaration(Property p) {
    if (p instanceof ArrayProperty) {
      ArrayProperty ap = (ArrayProperty) p;
      Property inner = ap.getItems();
      return getTypeDeclaration(inner) + "[]";
    } else if (p instanceof MapProperty) {
      MapProperty mp = (MapProperty) p;
      Property inner = mp.getAdditionalProperties();
      return getSwaggerType(p) + "[string," + getTypeDeclaration(inner) + "]";
    } else if (p instanceof RefProperty) {
      String type = super.getTypeDeclaration(p);
      return (!languageSpecificPrimitives.contains(type))
          ? modelPackage + "_" + type : type;
    }
    return super.getTypeDeclaration(p);
  }

  @Override
  public String getTypeDeclaration(String name) {
    if (!languageSpecificPrimitives.contains(name)) {
      return modelPackage + "_" + name;
    }
    return super.getTypeDeclaration(name);
  }

  @Override
  public String modelFileFolder() {
    return (outputFolder + "/" + toPackagePath(modelPackage(), srcBasePath));
  }

  @Override
  public void processOpts() {
    super.processOpts();

    if (additionalProperties.containsKey(PACKAGE_PATH)) {
      this.setPackagePath((String) additionalProperties.get(PACKAGE_PATH));
    } else {
      additionalProperties.put(PACKAGE_PATH, packagePath);
    }

    if (additionalProperties.containsKey(SRC_BASE_PATH)) {
      this.setSrcBasePath((String) additionalProperties.get(SRC_BASE_PATH));
    } else {
      additionalProperties.put(SRC_BASE_PATH, srcBasePath);
    }

    if (additionalProperties.containsKey(CodegenConstants.INVOKER_PACKAGE)) {
      this.setInvokerPackage(
          (String) additionalProperties.get(CodegenConstants.INVOKER_PACKAGE));
    } else {
      additionalProperties.put(CodegenConstants.INVOKER_PACKAGE,
          invokerPackage);
    }

    if (!additionalProperties.containsKey(CodegenConstants.MODEL_PACKAGE)) {
      additionalProperties.put(CodegenConstants.MODEL_PACKAGE, modelPackage);
    }

    if (!additionalProperties.containsKey(CodegenConstants.API_PACKAGE)) {
      additionalProperties.put(CodegenConstants.API_PACKAGE, modelPackage);
    }

    if (additionalProperties.containsKey(CodegenConstants.ARTIFACT_VERSION)) {
      this.setArtifactVersion(
          (String) additionalProperties.get(CodegenConstants.ARTIFACT_VERSION));
    } else {
      additionalProperties.put(CodegenConstants.ARTIFACT_VERSION,
          artifactVersion);
    }

    additionalProperties.put("escapedInvokerPackage",
        invokerPackage.replace("\\", "\\\\"));

    additionalProperties.put("fnSlimPath", new SlimPathLambda());
    additionalProperties.put("fnUpperCase", new UpperCaseLambda());
    additionalProperties.put("fnLowerCase", new LowerCaseLambda());
    additionalProperties.put("fnItemType", new ItemTypeLambda());

    additionalProperties.put("fnClassName", new ClassNameLambda());
    additionalProperties.put("fnMethodName", new MethodNameLambda());

    supportingFiles.add(new SupportingFile("index.mustache", getPackagePath(),
        "api/v" + artifactVersion + "/index.php"));
    supportingFiles.add(new SupportingFile(".htaccess", getPackagePath(),
        "api/v" + artifactVersion + "/.htaccess"));

    String restApiPath = "include/RestApi/";
    String[][] suppFiles = {
        {"field/", "TypeUtils/", "Field.class.php"},
        {"field/", "TypeUtils/", "BoolField.class.php"},
        {"field/", "TypeUtils/", "IntField.class.php"},
        {"field/", "TypeUtils/", "FloatField.class.php"},
        {"field/", "TypeUtils/", "StringField.class.php"},
        {"field/", "TypeUtils/", "ParseException.class.php"},
        {"restapi/", "", "Auth.class.php"},
        {"restapi/", "", "Params.class.php"},
        {"restapi/", "", "ProcessingException.class.php"},
        {"restapi/", "", "Response.class.php"},
        {"restapi/", "", "Make.class.php"},
        {"restapi/", "", "Result.class.php"},
        {"restapi/", "", "Role.class.php"}};

    for (String[] file : suppFiles) {
      supportingFiles.add(new SupportingFile(file[0] + file[2],
          getPackagePath() + restApiPath + file[1], file[2]));
    }

    additionalProperties.put("codegenVersion", CODEGEN_VERSION);
  }

  @Override
  public String removeNonNameElementToCamelCase(String name) {
    return name;
  }

  @Override
  public String toDefaultValue(Property p) {
    if (p instanceof StringProperty) {
      StringProperty dp = (StringProperty) p;
      if (dp.getDefault() != null) {
        return "'" + dp.getDefault() + "'";
      }
      return "null";
    }
    return super.toDefaultValue(p);
  }

  @Override
  public String toModelFilename(String name) {
    // should be the same as the model name
    return toModelName(name);
  }

  @Override
  public String toModelName(String name) {
    // model name cannot use reserved keyword
    if (reservedWords.contains(name)) {
      escapeReservedWord(name); // e.g. return => _return
    }
    // camelize the model name
    // phone_number => PhoneNumber
    return camelize(name);
  }

  @Override
  public String toParamName(String name) {
    return name;
  }

  private String getPackagePath() {
    return packagePath;
  }

  private void setArtifactVersion(String artifactVersion) {
    this.artifactVersion = artifactVersion;
  }

  private void setInvokerPackage(String invokerPackage) {
    this.invokerPackage = invokerPackage;
    srcBasePath = "include/" + invokerPackage;
    if (!additionalProperties.containsKey(CodegenConstants.API_PACKAGE)) {
      apiPackage = invokerPackage + "_Api";
    }
    if (!additionalProperties.containsKey(CodegenConstants.MODEL_PACKAGE)) {
      modelPackage = invokerPackage + "_Api_Model";
    }
  }

  private void setPackagePath(String packagePath) {
    this.packagePath = packagePath;
  }

  private void setSrcBasePath(String srcBasePath) {
    this.srcBasePath = srcBasePath;
  }

  private String toPackagePath(String packageName, String basePath) {
    String regFirstPathSeparator = "^[\\\\/]?";
    String regLastPathSeparator = "[\\\\/]?$";

    if (basePath != null && basePath.length() > 0) {
      basePath = basePath.replaceAll("_", "/").replaceAll(regLastPathSeparator,
          "") + File.separatorChar;
    }

    String packagePath = getPackagePath();
    if (!packagePath.isEmpty()) {
      packagePath += File.separatorChar;
    }

    packageName = packageName.replace(invokerPackage, "").replaceAll("_",
        "/").replaceAll(regFirstPathSeparator, "").replaceAll(
            regLastPathSeparator, "");

    return packagePath + basePath + packageName;
  }
}
