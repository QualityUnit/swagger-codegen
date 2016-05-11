package io.swagger.codegen.languages;

import java.io.File;
import java.util.Arrays;
import java.util.HashSet;

import io.swagger.codegen.CodegenConfig;
import io.swagger.codegen.CodegenConstants;
import io.swagger.codegen.CodegenType;
import io.swagger.codegen.DefaultCodegen;
import io.swagger.codegen.SupportingFile;

public class GwtClientCodegen extends DefaultCodegen implements CodegenConfig {

  private static final String LANGUAGE_NAME = "gwt-client";

  public static final String PACKAGE_PATH = "packagePath";

  protected String invokerPackage = "com.qualityunit.swagger.gwtclient";

  protected String artifactVersion = "1";
  protected String srcBasePath = "include/" + invokerPackage;

  public GwtClientCodegen() {
    super();
    outputFolder = "generated-code" + File.separator + "java";
    modelTemplateFiles.put("model.mustache", ".java");
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

    languageSpecificPrimitives = new HashSet<String>(
        Arrays.asList("String", "boolean", "Boolean", "Double", "Integer",
            "Long", "Float", "Object", "byte[]"));
    instantiationTypes.put("array", "ArrayList");
    instantiationTypes.put("map", "HashMap");
    typeMapping.put("date", "Date");
    typeMapping.put("file", "File");
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

  public String packageFolder() {
    return invokerPackage.replace('.', '/');
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

    supportingFiles.add(new SupportingFile("ValidationException.mustache",
        packageFolder(), "ValidationException.java"));
  }

  public void setArtifactVersion(String artifactVersion) {
    this.artifactVersion = artifactVersion;
  }

  public void setInvokerPackage(String invokerPackage) {
    this.invokerPackage = invokerPackage;
    apiPackage = invokerPackage;
    modelPackage = invokerPackage + ".model";
  }
}
