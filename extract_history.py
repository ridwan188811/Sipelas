import json
import os

transcript_path = r'C:\Users\User\.gemini\antigravity-ide\brain\d109cf2e-44a9-4fa0-b9b4-ae68202eb56c\.system_generated\logs\transcript.jsonl'

replacements = []

with open(transcript_path, 'r', encoding='utf-8') as f:
    for line in f:
        try:
            data = json.loads(line)
        except:
            continue
        
        tool_calls = data.get('tool_calls', [])
        for call in tool_calls:
            if call.get('name') == 'default_api:replace_file_content':
                args = call.get('arguments', {})
                if 'detail-pengajuan.blade.php' in args.get('TargetFile', ''):
                    replacements.append({
                        'file': args.get('TargetFile'),
                        'target': args.get('TargetContent'),
                        'replacement': args.get('ReplacementContent'),
                        'start': args.get('StartLine'),
                        'end': args.get('EndLine')
                    })

print(f'Found {len(replacements)} replacements.')

# Let's group by file and apply them backwards to the current file to get the original!
files_to_revert = set(r['file'] for r in replacements)

for file_path in files_to_revert:
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
            
        file_replacements = [r for r in replacements if r['file'] == file_path]
        
        # Apply backwards
        for r in reversed(file_replacements):
            # Replace the replacement with the target
            if r['replacement'] in content:
                content = content.replace(r['replacement'], r['target'])
            else:
                print(f"Warning: Could not find exact replacement block to revert in {os.path.basename(file_path)}")
                
        # Write to original_file.php
        out_path = file_path + '.original'
        with open(out_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Reverted {os.path.basename(file_path)} to {out_path}")
    except Exception as e:
        print(f"Error processing {file_path}: {e}")
