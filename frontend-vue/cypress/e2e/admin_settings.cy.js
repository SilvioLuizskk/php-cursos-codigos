describe("Admin settings E2E", () => {
    const api = "http://127.0.0.1:8100";

    it("atualiza configurações via API e valida via API (sanity check)", () => {
        const storeName = "E2E Loja " + Date.now();

        // Atualiza settings via API
        cy.request({
            method: "PUT",
            url: `${api}/api/admin/settings`,
            body: { store_name: storeName },
            failOnStatusCode: false,
        }).then((res) => {
            expect([200, 201]).to.include(res.status);

            // Validar via GET
            cy.request("GET", `${api}/api/admin/settings`).then((getRes) => {
                expect(getRes.status).to.eq(200);
                expect(getRes.body.store_name).to.eq(storeName);
            });
        });
    });
});
