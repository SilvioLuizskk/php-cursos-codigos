describe("Admin users E2E", () => {
    const api = "http://127.0.0.1:8100";

    it("cria usuário via API, verifica na UI e remove", () => {
        const timestamp = Date.now();
        const email = `e2e_user_${timestamp}@example.com`;

        // Criar usuário via API
        cy.request({
            method: "POST",
            url: `${api}/api/admin/users`,
            body: {
                name: "E2E User",
                email,
                password: "password123",
                role: "user",
            },
            failOnStatusCode: false,
        }).then((res) => {
            expect([200, 201]).to.include(res.status);
            const id = res.body?.id;
            // Verificar via API que o usuário foi criado (não existe página adminUsuarios neste projeto)
            cy.request('GET', `${api}/api/admin/users`).then((listRes) => {
                expect(listRes.status).to.eq(200);
                const body = listRes.body;
                const items = Array.isArray(body) ? body : body?.data || [];
                const found = Array.isArray(items) && items.find((u) => u.email === email);
                // Se não encontrar, falhar com informação útil
                expect(found, `Usuário criado deve estar presente na listagem (items: ${items.length})`).to.be.ok;

                // Cleanup: deletar via API
                if (id) {
                    cy.request('DELETE', `${api}/api/admin/users/${id}`).its('status').should('eq', 200);
                }
            });
        });
    });
});
