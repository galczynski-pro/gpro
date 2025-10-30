# Quick Start Guide - Persona Creator

## 🚀 Get Started in 2 Minutes

### Option 1: Use a Pre-made Template (Easiest)

Import one of our ready-to-use personas:

```bash
# Import Marketing Maven
node scripts/import-template.js scripts/persona-templates/marketing-guru.json

# Import Code Mentor
node scripts/import-template.js scripts/persona-templates/code-mentor.json

# Import Wellness Coach
node scripts/import-template.js scripts/persona-templates/wellness-coach.json

# Import Story Weaver (Creative Writer)
node scripts/import-template.js scripts/persona-templates/creative-storyteller.json

# Import Business Strategist
node scripts/import-template.js scripts/persona-templates/business-analyst.json
```

This generates SQL files in `scripts/generated-personas/` that you can import to your database.

### Option 2: Create Your Own Persona (Interactive)

Run the interactive creator:

```bash
node scripts/create-persona.js
```

Answer the prompts to build a custom persona with:
- Custom name, role, and description
- Style presets (professional, friendly, creative, etc.)
- AI model selection
- Temperature and creativity settings
- Advanced features (DALL-E, Vision, Voice)

## 📦 What's Included

### Pre-made Personas

| Persona | Style | Temperature | Features |
|---------|-------|-------------|----------|
| **Marketing Maven** | Enthusiastic & Creative | 0.8 | DALL-E, Vision |
| **Code Mentor** | Professional & Patient | 0.6 | Vision |
| **Wellness Guide** | Empathetic & Supportive | 0.7 | Voice Input |
| **Story Weaver** | Imaginative & Inspiring | 0.9 | DALL-E, Vision |
| **Business Strategist** | Authoritative & Analytical | 0.5 | Vision |

### Style Presets

Choose from 6 built-in styles when creating personas:

1. **Professional** (0.5) - Formal, business-like
2. **Friendly** (0.7) - Warm, conversational
3. **Expert** (0.6) - Authoritative, detailed
4. **Creative** (0.9) - Imaginative, innovative
5. **Concise** (0.4) - Brief, direct
6. **Empathetic** (0.7) - Understanding, supportive

## 🔧 Import to Database

After generating SQL files:

**Method 1: MySQL Command Line**
```bash
mysql -u username -p database_name < scripts/generated-personas/persona-name.sql
```

**Method 2: phpMyAdmin / Adminer**
1. Open the SQL file
2. Copy the INSERT statement
3. Paste in SQL query tab
4. Execute

**Method 3: Direct Import Script** (coming soon)
```bash
node scripts/import-to-db.js scripts/generated-personas/persona-name.sql
```

## ✅ Verify Import

Check if persona was imported successfully:

```sql
SELECT id, name, slug, expert, API_MODEL, temperature
FROM prompts
WHERE slug = 'marketing-maven';
```

## 🧪 Test Your Persona

Visit your persona in the browser:
```
https://your-domain.com/chat/[persona-slug]
```

Example:
```
https://your-domain.com/chat/marketing-maven
```

## 🎨 Customize Templates

Want to modify a template? Easy!

1. Copy a template file:
```bash
cp scripts/persona-templates/marketing-guru.json scripts/persona-templates/my-custom-persona.json
```

2. Edit the JSON file:
   - Change `name`, `slug`, `expert`, `description`
   - Modify the `prompt` (system instructions)
   - Adjust `temperature` (creativity level)
   - Enable/disable features (`use_dalle`, `use_vision`, etc.)

3. Import your custom template:
```bash
node scripts/import-template.js scripts/persona-templates/my-custom-persona.json
```

## 💡 Tips for Great Personas

### 1. System Prompt Structure
```
You are [Name], a [role] with expertise in [areas].

Your expertise includes:
- Specific skill 1
- Specific skill 2
- Specific skill 3

Your approach:
- How you interact
- What you prioritize
- Your methodology

Communication style:
- Tone and personality
- Language level
- Focus areas
```

### 2. Temperature Guide
- **0.0-0.3** = Focused, factual (legal, medical advice)
- **0.4-0.6** = Balanced (business, technical)
- **0.7-0.8** = Creative but controlled (teaching, coaching)
- **0.9-1.0** = Highly creative (art, storytelling)

### 3. Feature Selection
- **DALL-E**: Enable for design, visual concepts, image generation
- **Vision**: Enable for code review, document analysis, image understanding
- **Voice Input**: Enable for accessibility, hands-free interaction

### 4. Message History
- **6-8 messages**: Simple Q&A personas
- **10-12 messages**: Standard conversations
- **15+ messages**: Deep consultations, complex projects

## 🐛 Troubleshooting

**Persona doesn't appear in the app?**
- Ensure `status` is set to `'1'`
- Check slug is unique in database
- Verify SQL was executed successfully

**Responses are too random?**
- Lower the `temperature` value
- Increase `frequency_penalty`

**Responses are too rigid?**
- Increase `temperature` value
- Reduce penalties to 0
- Add more personality to the system prompt

**Features not working?**
- Check feature flags are `'1'` (string, not boolean)
- Verify API keys in main app settings
- Some features require user authentication

## 📚 Next Steps

1. **Read the full README**: `scripts/README.md`
2. **Explore templates**: Check `scripts/persona-templates/`
3. **Experiment**: Try different temperatures and styles
4. **Share**: Export and share your best personas!

## 🎯 Common Use Cases

### Customer Support Bot
```bash
# Use Friendly preset (0.7)
# Enable: Vision (for screenshots)
# Features: Empathetic tone, clear writing
```

### Code Review Assistant
```bash
# Use Expert preset (0.6)
# Enable: Vision (for code/diagrams)
# Features: Technical tone, step-by-step writing
```

### Content Writer
```bash
# Use Creative preset (0.9)
# Enable: DALL-E (for images)
# Features: Narrative style, enthusiastic tone
```

### Business Consultant
```bash
# Use Professional preset (0.5)
# Enable: Vision (for charts/reports)
# Features: Authoritative tone, detailed writing
```

---

**Need help?** Check the main README or review the example templates! 🤖
