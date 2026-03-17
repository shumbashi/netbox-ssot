const fs = require('fs');
const path = require('path');
const compiler = require('vue-template-compiler')
const params = buildParams();

if (typeof params.output == "undefined" || params.output === "")
{
    console.error("Output file path is not specified. Use --output=output_file_path");
    process.exit(1);
}

runCompile(params.output);

function runCompile(outputCompiledFilePath)
{
    console.log("Start compiling...");

    exportVariableToFile(compileComponentsTemplates(), outputCompiledFilePath)

    console.log("Compiled file output: " + outputCompiledFilePath);
}

function buildParams()
{
    const args = process.argv.slice(2);

    const parsedArgs = {};
    args.forEach(arg => {
        const [key, value] = arg.replace(/^--/, '').split('=');
        parsedArgs[key] = value;
    });

    return parsedArgs;
}

function compileTemplate(path)
{
    const res = compiler.compile(fs.readFileSync(path, 'utf-8'), {whitespace: 'condense'});

    if (res.errors.length > 0)
    {
        console.error(`Error compiling template at ${path}:`, res.errors);
        return "";
    }

    return res;
}

function compileComponentsTemplates()
{
    const componentsPath = path.join(__dirname, '../../../components');
    const moduleName = getModuleName(path.join(__dirname, '../../../'));
    const compiledTemplates = {};

    const components = findComponentsToCompile(componentsPath);

    components.forEach(function (component) {
        const componentPath = componentsPath + `/${component}/assets/component.html`;

        if (!fs.existsSync(componentPath))
        {
            return "";
        }

        const res = compileTemplate(componentPath);

        let staticFunction = [];
        res.staticRenderFns.forEach( (staticRenderFn) => {staticFunction.push(eval(`(function () { ${staticRenderFn} })`))});

        compiledTemplates[parseComponentName(moduleName, component)] = {
            render:  eval(`(function () { ${res.render} })`),
            staticRenderFns: staticFunction
        };
    })

    return compiledTemplates
}

function findComponentsToCompile(componentsPath)
{
    const entries = fs.readdirSync(componentsPath, { withFileTypes: true });

    return entries
        .filter(entry => entry.isDirectory())
        .map(entry => entry.name);
}

function exportVariableToFile(variableToExport, outputFilePath)
{
    const variableName = 'compiledTemplates';
    const content = `const ${variableName} = ` + serializeObject(variableToExport, 0) + ';';
    const dir = path.dirname(outputFilePath);

    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }

    fs.writeFileSync(outputFilePath, content, (err) => {
        if (err) {
            console.error('An error occurred during save file: ', err);
        }
    });
}

function parseComponentName(moduleName, componentName)
{
    const name = moduleName + "-" + componentName;
    return '"' + name.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase() + '"';
}

function getModuleName(moduleRootDir) {
    if (typeof moduleRootDir !== 'string') return '';
    return moduleRootDir.replace(/[/\\]$/, '').split(/[/\\]/).pop();
}

function serializeObject(obj, indent = 0) {
    const spacing = '  '.repeat(indent);

    if (Array.isArray(obj)) {
        const items = obj.map(item => serializeObject(item, indent + 1));
        return items.length > 0 ? `[\n${spacing}  ${items.join(`,\n${spacing}  `)}\n${spacing}]` : "[]";
    } else if (typeof obj === 'function') {
        return obj.toString();
    } else if (typeof obj === 'object' && obj !== null) {
        const props = Object.entries(obj).map(
            ([key, val]) => `${spacing}  ${key}: ${serializeObject(val, indent + 1)}`
        );
        return `{\n${props.join(',\n')}\n${spacing}}`;
    } else {
        return JSON.stringify(obj);
    }
}