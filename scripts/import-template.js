#!/usr/bin/env node

/**
 * Persona Template Importer for GPRO
 *
 * Imports persona templates from JSON files and generates SQL or imports directly to database
 *
 * Usage:
 *   node scripts/import-template.js <template-file.json>
 *   node scripts/import-template.js persona-templates/marketing-maven.json
 */

const fs = require('fs');
const path = require('path');

// ANSI color codes
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  cyan: '\x1b[36m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  red: '\x1b[31m',
  magenta: '\x1b[35m',
};

function printSuccess(message) {
  console.log(`${colors.green}✓ ${message}${colors.reset}`);
}

function printError(message) {
  console.log(`${colors.red}✗ ${message}${colors.reset}`);
}

function printInfo(message) {
  console.log(`${colors.yellow}ℹ ${message}${colors.reset}`);
}

function printHeader(message) {
  console.log(`${colors.cyan}${colors.bright}${message}${colors.reset}`);
}

function escapeString(str) {
  if (!str) return '';
  return str.replace(/'/g, "\\'").replace(/\n/g, '\\n').replace(/\r/g, '\\r');
}

function generateSQL(persona) {
  // Remove any metadata fields that start with underscore
  const cleanPersona = {};
  for (const [key, value] of Object.entries(persona)) {
    if (!key.startsWith('_')) {
      cleanPersona[key] = value;
    }
  }

  const fields = [];
  const values = [];

  for (const [key, value] of Object.entries(cleanPersona)) {
    fields.push(`\`${key}\``);

    if (typeof value === 'number') {
      values.push(value);
    } else if (value === null || value === undefined) {
      values.push('NULL');
    } else {
      values.push(`'${escapeString(String(value))}'`);
    }
  }

  return `-- Persona: ${persona.name}
-- Imported from template: ${new Date().toISOString()}
-- Slug: ${persona.slug}
-- Expert: ${persona.expert}

INSERT INTO \`prompts\` (${fields.join(', ')})
VALUES (${values.join(', ')});

-- Verify the import:
-- SELECT id, name, slug, expert, API_MODEL, temperature FROM prompts WHERE slug = '${persona.slug}';
`;
}

function displayPersonaInfo(persona) {
  console.log(`\n${colors.bright}Persona Details:${colors.reset}`);
  console.log(`  Name: ${colors.green}${persona.name}${colors.reset}`);
  console.log(`  Slug: ${colors.cyan}${persona.slug}${colors.reset}`);
  console.log(`  Expert: ${persona.expert}`);
  console.log(`  Description: ${persona.description}`);
  console.log(`  Model: ${colors.yellow}${persona.API_MODEL}${colors.reset}`);
  console.log(`  Temperature: ${persona.temperature}`);

  console.log(`\n${colors.bright}Features:${colors.reset}`);
  console.log(`  DALL-E: ${persona.use_dalle === '1' ? colors.green + '✓' : colors.yellow + '✗'}${colors.reset}`);
  console.log(`  Vision: ${persona.use_vision === '1' ? colors.green + '✓' : colors.yellow + '✗'}${colors.reset}`);
  console.log(`  Voice Input: ${persona.use_mic_whisper === '1' ? colors.green + '✓' : colors.yellow + '✗'}${colors.reset}`);

  if (persona._style_notes) {
    console.log(`\n${colors.bright}Style Notes:${colors.reset}`);
    console.log(`  Tone: ${persona._style_notes.tone}`);
    console.log(`  Writing Style: ${persona._style_notes.writing_style}`);
    console.log(`  Personality: ${persona._style_notes.personality}`);
  }

  console.log(`\n${colors.bright}System Prompt Preview:${colors.reset}`);
  const promptPreview = persona.prompt.substring(0, 200);
  console.log(`  ${promptPreview}...`);
}

async function main() {
  console.clear();
  printHeader(`
╔═══════════════════════════════════════════════╗
║                                               ║
║     📥 GPRO Persona Template Importer         ║
║                                               ║
╚═══════════════════════════════════════════════╝
  `);

  // Check if file argument is provided
  if (process.argv.length < 3) {
    printError('No template file specified!');
    console.log(`\n${colors.bright}Usage:${colors.reset}`);
    console.log(`  node scripts/import-template.js <template-file.json>`);
    console.log(`\n${colors.bright}Examples:${colors.reset}`);
    console.log(`  node scripts/import-template.js persona-templates/marketing-maven.json`);
    console.log(`  node scripts/import-template.js persona-templates/code-mentor.json`);
    console.log(`\n${colors.bright}Available templates:${colors.reset}`);

    const templatesDir = path.join(__dirname, 'persona-templates');
    if (fs.existsSync(templatesDir)) {
      const templates = fs.readdirSync(templatesDir).filter(f => f.endsWith('.json'));
      templates.forEach(t => console.log(`  - ${t}`));
    }
    process.exit(1);
  }

  const templateFile = process.argv[2];
  let templatePath = templateFile;

  // If relative path, resolve from current directory
  if (!path.isAbsolute(templatePath)) {
    templatePath = path.join(process.cwd(), templatePath);
  }

  // Check if file exists
  if (!fs.existsSync(templatePath)) {
    // Try relative to scripts directory
    const scriptRelativePath = path.join(__dirname, templateFile);
    if (fs.existsSync(scriptRelativePath)) {
      templatePath = scriptRelativePath;
    } else {
      printError(`Template file not found: ${templateFile}`);
      process.exit(1);
    }
  }

  printInfo(`Loading template: ${templatePath}\n`);

  // Load and parse JSON
  let persona;
  try {
    const jsonContent = fs.readFileSync(templatePath, 'utf8');
    persona = JSON.parse(jsonContent);
    printSuccess('Template loaded successfully');
  } catch (error) {
    printError(`Failed to parse JSON: ${error.message}`);
    process.exit(1);
  }

  // Validate required fields
  const requiredFields = ['name', 'slug', 'prompt'];
  const missingFields = requiredFields.filter(field => !persona[field]);

  if (missingFields.length > 0) {
    printError(`Missing required fields: ${missingFields.join(', ')}`);
    process.exit(1);
  }

  // Display persona information
  displayPersonaInfo(persona);

  // Generate SQL
  const sql = generateSQL(persona);

  // Create output directory
  const outputDir = path.join(__dirname, 'generated-personas');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  // Save SQL file
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-').substring(0, 19);
  const sqlFilename = `${persona.slug}-${timestamp}.sql`;
  const sqlFilepath = path.join(outputDir, sqlFilename);

  fs.writeFileSync(sqlFilepath, sql);
  printSuccess(`\nSQL file generated: ${sqlFilepath}`);

  // Also save clean JSON (without metadata)
  const cleanPersona = {};
  for (const [key, value] of Object.entries(persona)) {
    if (!key.startsWith('_')) {
      cleanPersona[key] = value;
    }
  }

  const jsonFilename = `${persona.slug}-${timestamp}.json`;
  const jsonFilepath = path.join(outputDir, jsonFilename);
  fs.writeFileSync(jsonFilepath, JSON.stringify(cleanPersona, null, 2));
  printSuccess(`JSON file saved: ${jsonFilepath}`);

  // Show import instructions
  console.log(`\n${colors.cyan}${colors.bright}Next Steps:${colors.reset}`);
  console.log(`\n${colors.bright}1. Import to database:${colors.reset}`);
  console.log(`   mysql -u [username] -p [database] < ${sqlFilepath}`);
  console.log(`\n${colors.bright}2. Or copy SQL and paste in phpMyAdmin/Adminer${colors.reset}`);
  console.log(`\n${colors.bright}3. Verify the import:${colors.reset}`);
  console.log(`   SELECT * FROM prompts WHERE slug = '${persona.slug}';`);
  console.log(`\n${colors.bright}4. Test the persona:${colors.reset}`);
  console.log(`   Visit: https://your-domain.com/chat/${persona.slug}`);

  printSuccess('\n✨ Template import complete!');
}

main().catch(error => {
  printError(`Unexpected error: ${error.message}`);
  console.error(error);
  process.exit(1);
});
