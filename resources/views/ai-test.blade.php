<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel Free AI Test</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width: 760px">
            <div class="card-body p-4">
                <h1 class="h3 mb-2">Laravel AI Playground</h1>
                <p class="text-secondary">
                    Test AI text generation using Groq.
                </p>

                <form id="aiForm">
                    <label for="prompt" class="form-label">
                        Your prompt
                    </label>

                    <textarea
                        id="prompt"
                        class="form-control mb-3"
                        rows="5"
                        maxlength="2000"
                        required
                        placeholder="Write a product description for pharmacy software..."
                    ></textarea>

                    <button id="submitButton"
                            class="btn btn-primary"
                            type="submit">
                        Generate with AI
                    </button>
                </form>

                <div id="status" class="mt-3" role="status"></div>

                <section class="mt-4">
                    <h2 class="h5">AI response</h2>
                    <pre id="result"
                         class="bg-light border rounded p-3 text-wrap"
                         style="white-space: pre-wrap"></pre>
                </section>
            </div>
        </div>
    </main>

    <script>
        const form = document.getElementById('aiForm');
        const button = document.getElementById('submitButton');
        const statusBox = document.getElementById('status');
        const resultBox = document.getElementById('result');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            button.disabled = true;
            statusBox.textContent = 'Generating response...';
            resultBox.textContent = '';

            try {
                const response = await fetch('/api/ai/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                    },
                    body: JSON.stringify({
                        prompt: document.getElementById('prompt').value
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'AI request failed.'
                    );
                }

                resultBox.textContent = data.result;
                statusBox.textContent = 'Generation completed.';
            } catch (error) {
                statusBox.textContent = error.message;
            } finally {
                button.disabled = false;
            }
        });
    </script>
</body>
</html>