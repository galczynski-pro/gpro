# GPRO Persona Creation Scripts

This directory contains tools for creating and managing complex AI personas with custom styles and tones for the GPRO application.

## Files

- **create-persona.js** - Interactive CLI tool for creating custom personas
- **import-template.js** - Import pre-made persona templates
- **persona-templates/** - Directory containing example persona templates

## Quick Start

### Creating a New Persona Interactively

```bash
node scripts/create-persona.js
```

This will guide you through an interactive process to create a complex persona with:
- Basic information (name, expert field, description)
- Style & tone presets
- Custom AI system prompt
- Model selection and parameters
- Advanced features (DALL-E, Vision, Voice)

The script generates both SQL and JSON files that can be imported into your database.

### Using Pre-made Templates

We've created several example personas with different styles and tones:

1. **Marketing Maven** - Enthusiastic digital marketing strategist
2. **Code Mentor** - Patient and professional programming expert
3. **Wellness Guide** - Empathetic health and wellness coach
4. **Story Weaver** - Creative and imaginative storytelling expert
5. **Business Strategist** - Data-driven business analyst

To import a template:

```bash
node scripts/import-template.js persona-templates/marketing-maven.json
```

Or import directly from SQL (generated personas):

```bash
mysql -u username -p database_name < scripts/generated-personas/persona-name.sql
```

## Persona Style Guide

### Style Presets

The interactive creator includes six style presets:

| Preset | Temperature | Best For |
|--------|-------------|----------|
| **Professional** | 0.5 | Business, formal communication |
| **Friendly** | 0.7 | Customer service, general assistance |
| **Expert** | 0.6 | Technical advice, consulting |
| **Creative** | 0.9 | Writing, brainstorming, art |
| **Concise** | 0.4 | Quick answers, summaries |
| **Empathetic** | 0.7 | Support, coaching, counseling |

### Temperature Guide

- **0.0-0.3**: Very focused and deterministic (ideal for factual tasks)
- **0.4-0.6**: Balanced (good for most professional use cases)
- **0.7-0.8**: Creative but controlled (conversational, helpful)
- **0.9-1.0**: Highly creative (storytelling, brainstorming)

### Tone Options

Tones can be mixed with styles for fine-tuned personality:

- **Professional** - Formal and business-like
- **Casual** - Relaxed and conversational
- **Enthusiastic** - Energetic and positive
- **Empathetic** - Understanding and supportive
- **Authoritative** - Confident and commanding
- **Humorous** - Light-hearted and funny

### Writing Style Options

- **Detailed** - Comprehensive explanations
- **Concise** - Brief and to-the-point
- **Step-by-step** - Clear numbered instructions
- **Narrative** - Story-like flow
- **Technical** - Precise technical language
- **Simple** - Easy-to-understand

## Creating Custom Personas

### Best Practices for System Prompts

1. **Define the role clearly**
   ```
   You are [Name], a [role] with expertise in [areas]...
   ```

2. **List specific expertise areas**
   ```
   Your expertise includes:
   - Area 1
   - Area 2
   - Area 3
   ```

3. **Specify the approach/methodology**
   ```
   Your approach:
   - Ask clarifying questions
   - Provide actionable advice
   - Use examples
   ```

4. **Define communication style**
   ```
   Communication style:
   - Be [tone]
   - Use [language level]
   - Focus on [priorities]
   ```

5. **Set boundaries (if applicable)**
   ```
   Important disclaimers:
   - You are not a [medical/legal] professional
   - Always recommend professional help for [serious issues]
   ```

### Example Persona Structures

**For Technical Experts:**
- Lower temperature (0.5-0.6)
- Enable Vision for code/diagram reading
- Higher message history (12-15)
- Step-by-step writing style

**For Creative Roles:**
- Higher temperature (0.8-0.9)
- Enable DALL-E for image generation
- Higher frequency/presence penalties for variety
- Narrative writing style

**For Coaches/Advisors:**
- Moderate temperature (0.7)
- Enable Voice input for accessibility
- Empathetic tone
- Longer message history for context

**For Business/Analytical:**
- Lower temperature (0.4-0.6)
- Professional tone
- Detailed writing style
- Enable Vision for charts/reports

## Advanced Features

### Voice & Audio
- **Google Text-to-Speech** - Free system voices
- **Google Cloud Voice** - Premium voices with gender options
- **Whisper Speech-to-Text** - Enable voice input

### AI Capabilities
- **DALL-E** - Image generation (requires user login)
- **Vision** - Read and analyze images
- **Multiple Models** - gpt-4o, gpt-4-turbo, gpt-3.5-turbo

### Display Options
Control what users see in the chat interface:
- Avatar display
- Model information
- Copy button
- Share functionality
- Message suggestions
- Contact list
- Language/tone/style selectors

## File Structure

```
scripts/
├── create-persona.js           # Interactive creator
├── import-template.js          # Template importer
├── README.md                   # This file
├── persona-templates/          # Pre-made templates
│   ├── marketing-maven.json
│   ├── code-mentor.json
│   ├── wellness-guide.json
│   ├── story-weaver.json
│   └── business-strategist.json
└── generated-personas/         # Output directory
    ├── persona-name.json       # JSON config
    └── persona-name.sql        # SQL insert
```

## Customizing Templates

You can modify the JSON templates to create your own:

1. Copy an existing template
2. Edit the fields:
   - `name`, `slug`, `expert`, `description`
   - `prompt` (system prompt)
   - `temperature`, `frequency_penalty`, `presence_penalty`
   - Feature flags (`use_dalle`, `use_vision`, etc.)
3. Save with a new filename
4. Import using `import-template.js`

## Database Schema

Personas are stored in the `prompts` table with these key fields:

- **Identity**: name, slug, expert, description, image
- **AI Config**: API_MODEL, prompt, temperature, penalties
- **Features**: use_dalle, use_vision, use_mic_whisper
- **Chat**: message history limits, length constraints
- **Display**: UI element toggles
- **Voice**: Google TTS and Cloud Voice settings

## Tips

1. **Test different temperatures** - Small changes can significantly affect personality
2. **Use examples in prompts** - Show the persona how to respond
3. **Be specific about expertise** - List concrete areas of knowledge
4. **Define boundaries** - Tell the persona what it should NOT do
5. **Iterate and refine** - Test conversations and adjust the prompt
6. **Consider the audience** - Match tone and complexity to target users
7. **Use message history wisely** - More context = better continuity, but higher costs

## Troubleshooting

**Persona not appearing?**
- Check `status` field is set to '1'
- Verify slug is unique
- Ensure database connection is working

**Responses too random?**
- Lower the temperature
- Increase frequency_penalty to reduce repetition

**Responses too rigid?**
- Increase temperature
- Reduce penalties
- Add more personality to the prompt

**Features not working?**
- Check feature flags are set to '1'
- Verify API keys are configured in main settings
- Some features require user login

## Support

For issues or questions:
1. Check the GPRO documentation
2. Review example templates for guidance
3. Test with different style presets
4. Adjust one parameter at a time

Happy persona creating! 🤖
