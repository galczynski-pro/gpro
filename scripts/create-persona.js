#!/usr/bin/env node

/**
 * Interactive Persona Creator for GPRO
 *
 * This script helps you create complex AI personas with custom styles and tones
 * for use in the GPRO application.
 *
 * Usage: node scripts/create-persona.js
 */

const readline = require('readline');
const fs = require('fs');
const path = require('path');

// ANSI color codes for terminal output
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  cyan: '\x1b[36m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
};

// Create readline interface
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

// Helper function to prompt user
function prompt(question) {
  return new Promise((resolve) => {
    rl.question(question, resolve);
  });
}

// Helper function to create slug from name
function createSlug(name) {
  return name.toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

// Section header
function printSection(title) {
  console.log(`\n${colors.cyan}${colors.bright}━━━ ${title} ━━━${colors.reset}\n`);
}

// Success message
function printSuccess(message) {
  console.log(`${colors.green}✓ ${message}${colors.reset}`);
}

// Info message
function printInfo(message) {
  console.log(`${colors.yellow}ℹ ${message}${colors.reset}`);
}

// Persona template with defaults
const personaTemplate = {
  // Basic Information
  name: '',
  slug: '',
  expert: '',
  description: '',
  welcome_message: '',
  image: 'default-avatar.png',
  status: '1',
  item_order: 999,

  // AI Model Configuration
  API_MODEL: 'gpt-4o',
  prompt: '',
  temperature: 0.7,
  frequency_penalty: 0,
  presence_penalty: 0,

  // Chat Settings
  chat_minlength: 1,
  chat_maxlength: 10000,
  array_message_history: 10,
  array_message_limit_length: 500,

  // Voice Settings
  use_google_voice: '0',
  google_voice: '',
  google_voice_lang_code: 'en-US',
  use_cloud_google_voice: '0',
  cloud_google_voice: '',
  cloud_google_voice_lang_code: 'en-US',
  cloud_google_voice_gender: 'NEUTRAL',
  display_mp3_google_cloud_text: '0',

  // Microphone Settings
  use_mic_whisper: '0',
  display_mic: '1',
  mic_speak_lang: 'en-US',

  // Advanced Features
  use_dalle: '0',
  use_vision: '0',

  // Display Options
  display_avatar: '1',
  display_copy_btn: '1',
  display_description: '1',
  display_welcome_message: '1',
  display_API_MODEL: '0',
  display_contacts_user_list: '0',
  display_share: '1',
  display_prompts_output: '1',
  display_prompts_tone: '1',
  display_prompts_writing: '1',
  display_suggestions: '1',

  // Filters and Options
  filter_badwords: '0',
  allow_embed_chat: '0',

  // Default Style Options
  id_prompts_output_default: 0,
  id_prompts_tone_default: 0,
  id_prompts_writing_default: 0,
};

// Style presets for different persona types
const stylePresets = {
  professional: {
    description: 'Formal, business-like, and professional',
    prompt_suffix: 'Maintain a professional, formal tone. Use industry-standard terminology and provide structured, business-appropriate responses.',
    temperature: 0.5,
  },
  friendly: {
    description: 'Warm, approachable, and conversational',
    prompt_suffix: 'Be warm, friendly, and conversational. Use a casual but respectful tone that makes users feel comfortable.',
    temperature: 0.7,
  },
  expert: {
    description: 'Authoritative, detailed, and knowledgeable',
    prompt_suffix: 'Demonstrate deep expertise and knowledge. Provide detailed, accurate information with authoritative confidence.',
    temperature: 0.6,
  },
  creative: {
    description: 'Imaginative, expressive, and innovative',
    prompt_suffix: 'Be creative, imaginative, and expressive. Think outside the box and provide innovative ideas and perspectives.',
    temperature: 0.9,
  },
  concise: {
    description: 'Brief, direct, and to-the-point',
    prompt_suffix: 'Be concise and direct. Provide clear, brief answers without unnecessary elaboration.',
    temperature: 0.4,
  },
  empathetic: {
    description: 'Understanding, supportive, and compassionate',
    prompt_suffix: 'Show empathy and understanding. Be supportive, compassionate, and attentive to the user\'s emotional needs.',
    temperature: 0.7,
  },
};

// Tone options
const toneOptions = [
  { name: 'Professional', description: 'Formal and business-like' },
  { name: 'Casual', description: 'Relaxed and conversational' },
  { name: 'Enthusiastic', description: 'Energetic and positive' },
  { name: 'Empathetic', description: 'Understanding and supportive' },
  { name: 'Authoritative', description: 'Confident and commanding' },
  { name: 'Humorous', description: 'Light-hearted and funny' },
];

// Writing style options
const writingStyleOptions = [
  { name: 'Detailed', description: 'Comprehensive and thorough explanations' },
  { name: 'Concise', description: 'Brief and to-the-point' },
  { name: 'Step-by-step', description: 'Clear instructions with numbered steps' },
  { name: 'Narrative', description: 'Story-like and flowing' },
  { name: 'Technical', description: 'Precise technical language' },
  { name: 'Simple', description: 'Easy-to-understand language' },
];

async function main() {
  console.clear();
  console.log(`${colors.bright}${colors.magenta}
╔═══════════════════════════════════════════════╗
║                                               ║
║     🤖 GPRO Persona Creator v1.0              ║
║     Create Complex AI Personas                ║
║                                               ║
╚═══════════════════════════════════════════════╝
${colors.reset}`);

  printInfo('This interactive tool will guide you through creating a complex AI persona.');
  printInfo('Press Ctrl+C at any time to cancel.\n');

  const persona = { ...personaTemplate };

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // BASIC INFORMATION
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  printSection('1. Basic Information');

  persona.name = await prompt(`${colors.bright}Persona Name:${colors.reset} `);
  if (!persona.name) {
    console.log(`${colors.yellow}Name is required!${colors.reset}`);
    rl.close();
    return;
  }

  const suggestedSlug = createSlug(persona.name);
  const slugInput = await prompt(`${colors.bright}URL Slug${colors.reset} [${suggestedSlug}]: `);
  persona.slug = slugInput || suggestedSlug;

  persona.expert = await prompt(`${colors.bright}Expert Field${colors.reset} (e.g., "Marketing Expert", "Data Scientist"): `);

  persona.description = await prompt(`${colors.bright}Short Description:${colors.reset} `);

  persona.welcome_message = await prompt(`${colors.bright}Welcome Message${colors.reset} (optional): `);

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // STYLE & TONE
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  printSection('2. Style & Tone');

  console.log(`${colors.bright}Available Style Presets:${colors.reset}\n`);
  const presetKeys = Object.keys(stylePresets);
  presetKeys.forEach((key, index) => {
    console.log(`  ${index + 1}. ${colors.green}${key}${colors.reset} - ${stylePresets[key].description}`);
  });
  console.log(`  ${presetKeys.length + 1}. ${colors.green}custom${colors.reset} - Define your own style\n`);

  const styleChoice = await prompt(`${colors.bright}Choose a style preset (1-${presetKeys.length + 1}):${colors.reset} `);
  const styleIndex = parseInt(styleChoice) - 1;

  let selectedPreset = null;
  if (styleIndex >= 0 && styleIndex < presetKeys.length) {
    selectedPreset = stylePresets[presetKeys[styleIndex]];
    printSuccess(`Selected: ${presetKeys[styleIndex]}`);
    persona.temperature = selectedPreset.temperature;
  }

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // AI SYSTEM PROMPT
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  printSection('3. AI System Prompt');

  printInfo('The system prompt defines the persona\'s behavior, expertise, and how it responds.');
  printInfo('This is the most important part of your persona.\n');

  console.log(`${colors.bright}Enter the system prompt (end with an empty line):${colors.reset}`);

  let promptLines = [];
  let line = '';
  do {
    line = await prompt('');
    if (line) promptLines.push(line);
  } while (line);

  persona.prompt = promptLines.join('\n');

  // Add style-specific suffix if a preset was chosen
  if (selectedPreset) {
    persona.prompt += '\n\n' + selectedPreset.prompt_suffix;
  }

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // AI MODEL & PARAMETERS
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  printSection('4. AI Model & Parameters');

  console.log(`${colors.bright}Available Models:${colors.reset}`);
  console.log('  1. gpt-4o (recommended - most capable)');
  console.log('  2. gpt-4-turbo');
  console.log('  3. gpt-3.5-turbo (faster, cheaper)\n');

  const modelChoice = await prompt(`${colors.bright}Choose model (1-3)${colors.reset} [1]: `);
  const models = ['gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo'];
  persona.API_MODEL = models[parseInt(modelChoice || '1') - 1] || 'gpt-4o';

  const tempInput = await prompt(`${colors.bright}Temperature (0-1)${colors.reset} [${persona.temperature}]: `);
  if (tempInput) persona.temperature = parseFloat(tempInput);

  const freqInput = await prompt(`${colors.bright}Frequency Penalty (0-1)${colors.reset} [${persona.frequency_penalty}]: `);
  if (freqInput) persona.frequency_penalty = parseFloat(freqInput);

  const presInput = await prompt(`${colors.bright}Presence Penalty (0-1)${colors.reset} [${persona.presence_penalty}]: `);
  if (presInput) persona.presence_penalty = parseFloat(presInput);

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // ADVANCED FEATURES
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  printSection('5. Advanced Features');

  const enableDalle = await prompt(`${colors.bright}Enable DALL-E image generation?${colors.reset} (y/n) [n]: `);
  persona.use_dalle = enableDalle.toLowerCase() === 'y' ? '1' : '0';

  const enableVision = await prompt(`${colors.bright}Enable vision (image reading)?${colors.reset} (y/n) [n]: `);
  persona.use_vision = enableVision.toLowerCase() === 'y' ? '1' : '0';

  const enableWhisper = await prompt(`${colors.bright}Enable voice input (Whisper)?${colors.reset} (y/n) [n]: `);
  persona.use_mic_whisper = enableWhisper.toLowerCase() === 'y' ? '1' : '0';

  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  // SUMMARY & SAVE
  // ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  printSection('Summary');

  console.log(`${colors.bright}Persona Configuration:${colors.reset}`);
  console.log(`  Name: ${colors.green}${persona.name}${colors.reset}`);
  console.log(`  Slug: ${colors.green}${persona.slug}${colors.reset}`);
  console.log(`  Expert: ${colors.green}${persona.expert}${colors.reset}`);
  console.log(`  Model: ${colors.green}${persona.API_MODEL}${colors.reset}`);
  console.log(`  Temperature: ${colors.green}${persona.temperature}${colors.reset}`);
  console.log(`  DALL-E: ${persona.use_dalle === '1' ? colors.green + 'Yes' : colors.yellow + 'No'}${colors.reset}`);
  console.log(`  Vision: ${persona.use_vision === '1' ? colors.green + 'Yes' : colors.yellow + 'No'}${colors.reset}`);
  console.log(`  Voice Input: ${persona.use_mic_whisper === '1' ? colors.green + 'Yes' : colors.yellow + 'No'}${colors.reset}\n`);

  const confirm = await prompt(`${colors.bright}Save this persona?${colors.reset} (y/n): `);

  if (confirm.toLowerCase() !== 'y') {
    console.log(`${colors.yellow}Cancelled.${colors.reset}`);
    rl.close();
    return;
  }

  // Generate SQL
  const sql = generateSQL(persona);

  // Save to file
  const outputDir = path.join(__dirname, 'generated-personas');
  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  const timestamp = new Date().toISOString().replace(/[:.]/g, '-').substring(0, 19);
  const filename = `${persona.slug}-${timestamp}.sql`;
  const filepath = path.join(outputDir, filename);

  fs.writeFileSync(filepath, sql);

  printSuccess(`Persona saved to: ${filepath}`);
  console.log(`\n${colors.cyan}To import this persona into your database, run:${colors.reset}`);
  console.log(`${colors.bright}mysql -u [username] -p [database] < ${filepath}${colors.reset}\n`);

  // Also save as JSON for reference
  const jsonFilename = `${persona.slug}-${timestamp}.json`;
  const jsonFilepath = path.join(outputDir, jsonFilename);
  fs.writeFileSync(jsonFilepath, JSON.stringify(persona, null, 2));
  printSuccess(`JSON configuration saved to: ${jsonFilepath}`);

  rl.close();
}

function generateSQL(persona) {
  const escapeString = (str) => {
    if (!str) return '';
    return str.replace(/'/g, "\\'").replace(/\n/g, '\\n');
  };

  const fields = [];
  const values = [];

  for (const [key, value] of Object.entries(persona)) {
    fields.push(`\`${key}\``);

    if (typeof value === 'number') {
      values.push(value);
    } else {
      values.push(`'${escapeString(value)}'`);
    }
  }

  return `-- Persona: ${persona.name}
-- Generated: ${new Date().toISOString()}
-- Slug: ${persona.slug}

INSERT INTO \`prompts\` (${fields.join(', ')})
VALUES (${values.join(', ')});

-- To verify the insert, run:
-- SELECT * FROM prompts WHERE slug = '${persona.slug}';
`;
}

// Handle graceful exit
process.on('SIGINT', () => {
  console.log(`\n\n${colors.yellow}Cancelled by user.${colors.reset}`);
  rl.close();
  process.exit(0);
});

// Run main function
main().catch((error) => {
  console.error(`${colors.yellow}Error:${colors.reset}`, error);
  rl.close();
  process.exit(1);
});
